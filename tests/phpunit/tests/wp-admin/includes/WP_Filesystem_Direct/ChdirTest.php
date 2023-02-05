<?php
/**
 * Tests for the WP_Filesystem_Direct::chdir() method.
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
 * @covers WP_Filesystem_Direct::chdir
 */
class Tests_Chdir extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::chdir()`
	 * returns false for a path that does not exist.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 *
	 * @param string $path The path.
	 */
	public function test_should_fail_to_change_directory( $path ) {
		$this->assertFalse( self::$filesystem->chdir( self::$file_structure['test_dir']['path'] . $path ) );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::chdir()` changes to
	 * an existing directory.
	 */
	public function test_should_change_directory() {
		$this->assertTrue( self::$filesystem->chdir( self::$file_structure['test_dir']['path'] ) );
	}

}
