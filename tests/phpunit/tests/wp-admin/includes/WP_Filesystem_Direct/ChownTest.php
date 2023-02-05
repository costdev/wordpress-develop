<?php
/**
 * Tests for the WP_Filesystem_Direct::chown() method.
 *
 * @package WordPress
 */

namespace WordPress\Tests\WP_Admin\Includes\WP_Filesystem_Direct;

require_once __DIR__ . '/base.php';

/**
 * @ticket
 *
 * @group admin
 * @group filesystem
 * @group filesystem-direct
 *
 * @covers WP_Filesystem_Direct::chown
 */
class Tests_Chown extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::chown()`
	 * returns false for a path that does not exist.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 *
	 * @param string $path The path.
	 */
	public function test_should_return_false( $path ) {
		$this->assertFalse( self::$filesystem->chown( $path, fileowner( __FILE__ ) ) );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::chown()`
	 * recursively changes the file owner.
	 */
	public function test_should_recurse() {
		$default_owner = fileowner( ABSPATH );
		$owner         = $default_owner + 1;

		$set     = self::$filesystem->chown( self::$file_structure['test_dir']['path'], $owner, true );
		$actual  = array( self::$file_structure['test_dir']['path'] => fileowner( self::$file_structure['test_dir']['path'] ) );
		$dirlist = self::$filesystem->dirlist( self::$file_structure['test_dir']['path'], true, true );

		foreach ( $dirlist as $file => $file_data ) {
			$actual[ $file ] = fileowner( self::$file_structure['test_dir']['path'] . $file );

			if ( 'd' === $file_data['type'] && ! empty( $file_data['files'] ) ) {
				foreach ( $file_data['files'] as $sub_file => $sub_file_data ) {
					$actual[ $sub_file ] = fileowner( self::$file_structure['test_dir']['path'] . $file . '/' . $sub_file );

					// Reset sub files.
					chown( self::$file_structure['test_dir']['path'] . $file . '/' . $sub_file, $default_owner );
				}
			}

			// Reset root files.
			chown( self::$file_structure['test_dir']['path'] . $file, $default_owner );
		}

		$this->assertTrue( $set, 'The owner was not set.' );
		$this->assertSame(
			array(
				self::$file_structure['test_dir']['path'] => $owner,
				'a_file_that_exists.txt'                  => $owner,
				'subdir'                                  => $owner,
				'subfile.txt'                             => $owner,
				'.a_hidden_file'                          => $owner,
			),
			$actual,
			'The owner of the contents is incorrect.'
		);
	}

	/**
	 * Tests that `WP_Filesystem_Direct::chown()`
	 * returns true when a directory listing cannot be retrieved.
	 */
	public function test_should_return_true_when_dirlist_fails() {
		global $wp_filesystem;

		$path = self::$file_structure['test_dir']['path'];

		// Set up mock filesystem.
		$filesystem_mock = $this->getMockBuilder( 'WP_Filesystem_Direct' )
								->setConstructorArgs( array( null ) )
								// Note: setMethods() is deprecated in PHPUnit 9, but still supported.
								->setMethods( array( 'is_dir', 'dirlist' ) )
								->getMock();

		$filesystem_mock->expects( $this->once() )->method( 'is_dir' )->willReturn( true );
		$filesystem_mock->expects( $this->once() )->method( 'dirlist' )->willReturn( false );

		$wp_filesystem_backup = $wp_filesystem;
		$wp_filesystem        = $filesystem_mock;

		$actual        = $filesystem_mock->chown( $path, fileowner( ABSPATH ), true );
		$wp_filesystem = $wp_filesystem_backup;

		$this->assertTrue( $actual );
	}

}
