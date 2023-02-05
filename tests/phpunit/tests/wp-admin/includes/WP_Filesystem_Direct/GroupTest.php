<?php
/**
 * Tests for the WP_Filesystem_Direct::group() method.
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
 * @covers WP_Filesystem_Direct::group
 */
class Tests_Group extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::group()` gets a file's group.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 * @dataProvider data_paths_that_exist
	 *
	 * @param string $path     The path.
	 * @param bool   $expected The expected result.
	 */
	public function test_should_get_path_group( $path, $expected ) {
		$actual = false !== self::$filesystem->group( self::$file_structure['test_dir']['path'] . $path );
		$this->assertSame( $expected, $actual );
	}

}
