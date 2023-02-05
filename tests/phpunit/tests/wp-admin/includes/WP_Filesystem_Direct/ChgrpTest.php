<?php
/**
 * Tests for the WP_Filesystem_Direct::chgrp() method.
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
 * @covers WP_Filesystem_Direct::chgrp
 */
class Tests_Chgrp extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::chgrp()`
	 * returns false for a path that does not exist.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 *
	 * @param string $path The path.
	 */
	public function test_should_fail_to_change_file_group( $path ) {
		$this->assertFalse( self::$filesystem->chgrp( self::$file_structure['test_dir']['path'] . $path, 0 ) );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::chgrp()`
	 * recursively changes the file group.
	 */
	public function test_should_recurse() {
		$default_group = filegroup( ABSPATH );
		$group         = $default_group + 1;

		$set     = self::$filesystem->chgrp( self::$file_structure['test_dir']['path'], $group, true );
		$actual  = array( self::$file_structure['test_dir']['path'] => filegroup( self::$file_structure['test_dir']['path'] ) );
		$dirlist = self::$filesystem->dirlist( self::$file_structure['test_dir']['path'], true, true );

		foreach ( $dirlist as $file => $file_data ) {
			$actual[ $file ] = filegroup( self::$file_structure['test_dir']['path'] . $file );

			if ( 'd' === $file_data['type'] && ! empty( $file_data['files'] ) ) {
				foreach ( $file_data['files'] as $sub_file => $sub_file_data ) {
					$actual[ $sub_file ] = filegroup( self::$file_structure['test_dir']['path'] . $file . '/' . $sub_file );

					// Reset sub files.
					chgrp( self::$file_structure['test_dir']['path'] . $file . '/' . $sub_file, $default_group );
				}
			}

			// Reset root files.
			chgrp( self::$file_structure['test_dir']['path'] . $file, $default_group );
		}

		$this->assertTrue( $set, 'The group was not set.' );
		$this->assertSame(
			array(
				self::$file_structure['test_dir']['path'] => $group,
				'a_file_that_exists.txt'                  => $group,
				'subdir'                                  => $group,
				'subfile.txt'                             => $group,
				'.a_hidden_file'                          => $group,
			),
			$actual,
			'The group of the contents is incorrect.'
		);
	}

}
