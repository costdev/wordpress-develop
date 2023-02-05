<?php
/**
 * Tests for the WP_Filesystem_Direct::exists() method.
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
 * @covers WP_Filesystem_Direct::exists
 */
class Tests_Exists extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::exists()` returns the correct value
	 * when checking whether a file or directory exists.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 * @dataProvider data_paths_that_exist
	 *
	 * @param string $path     The path to check.
	 * @param bool   $expected The expected result.
	 */
	public function test_should_check_for_an_existing_file_or_directory( $path, $expected ) {
		$this->assertSame( $expected, self::$filesystem->exists( self::$file_structure['test_dir']['path'] . $path ) );
	}

}
