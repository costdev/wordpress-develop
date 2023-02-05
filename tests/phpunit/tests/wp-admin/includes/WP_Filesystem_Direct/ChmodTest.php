<?php
/**
 * Tests for the WP_Filesystem_Direct::chmod() method.
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
 * @covers WP_Filesystem_Direct::chmod
 */
class Tests_Chmod extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::chmod()`
	 * returns false for a path that does not exist.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 *
	 * @param string $path The path.
	 */
	public function test_should_return_false( $path ) {
		$this->assertFalse( self::$filesystem->chmod( $path ) );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::chmod()` should set
	 * $mode when it is not passed.
	 *
	 * @dataProvider data_should_set_mode_when_not_passed
	 *
	 * @param string $path The path.
	 * @param string $type The type of path. "FILE" for file, "DIR" for directory.
	 */
	public function test_should_handle_set_mode_when_not_passed( $path, $type ) {
		$constant = 'FS_CHMOD_' . $type;
		if ( ! defined( $constant ) ) {
			define( $constant, ( 'FILE' === $type ? 0644 : 0755 ) );
		}

		$this->assertTrue( self::$filesystem->chmod( self::$file_structure['test_dir']['path'] . $path, false ) );
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_set_mode_when_not_passed() {
		return array(
			'a file'      => array(
				'path' => 'a_file_that_exists.txt',
				'type' => 'FILE',
			),
			'a directory' => array(
				'path' => '',
				'type' => 'DIR',
			),
		);
	}

	/**
	 * Tests that `WP_Filesystem_Direct::chmod()`
	 * recursively changes permissions.
	 */
	public function test_should_recurse() {
		$mode     = 0777;
		$mode_str = '0777';

		$set     = self::$filesystem->chmod( self::$file_structure['test_dir']['path'], $mode, true );
		$actual  = array( self::$file_structure['test_dir']['path'] => substr( sprintf( '%o', fileperms( self::$file_structure['test_dir']['path'] ) ), -4 ) );
		$dirlist = self::$filesystem->dirlist( self::$file_structure['test_dir']['path'], true, true );

		foreach ( $dirlist as $file => $file_data ) {
			$actual[ $file ] = $file_data['permsn'];

			if ( 'd' === $file_data['type'] && ! empty( $file_data['files'] ) ) {
				foreach ( $file_data['files'] as $sub_file => $sub_file_data ) {
					$actual[ $sub_file ] = $sub_file_data['permsn'];

					// Reset sub files.
					chmod( self::$file_structure['test_dir']['path'] . $file . '/' . $sub_file, $mode );
				}
			}

			// Reset root files.
			chmod( self::$file_structure['test_dir']['path'] . $file, 0755 );
		}

		$this->assertTrue( $set, 'The permissions were not set.' );
		$this->assertSame(
			array(
				self::$file_structure['test_dir']['path'] => $mode_str,
				'a_file_that_exists.txt'                  => $mode_str,
				'subdir'                                  => $mode_str,
				'subfile.txt'                             => $mode_str,
				'.a_hidden_file'                          => $mode_str,
			),
			$actual,
			'The permissions of the contents is incorrect.'
		);
	}

	/**
	 * Tests that `WP_Filesystem_Direct::chmod()`
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

		$actual        = $filesystem_mock->chmod( $path, 0755, true );
		$wp_filesystem = $wp_filesystem_backup;

		$this->assertTrue( $actual );
	}

}
