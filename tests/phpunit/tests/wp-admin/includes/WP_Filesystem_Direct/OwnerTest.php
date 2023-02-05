<?php
/**
 * Tests for the WP_Filesystem_Direct::owner() method.
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
 * @covers WP_Filesystem_Direct::owner
 */
class Tests_Owner extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::owner()` returns the
	 * owner of a path.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 * @dataProvider data_paths_that_exist
	 *
	 * @param string $path     The path.
	 * @param bool   $expected The expected result.
	 */
	public function test_should_get_path_owner( $path, $expected ) {
		$actual = false !== self::$filesystem->owner( self::$file_structure['test_dir']['path'] . $path );
		$this->assertSame( $expected, $actual );
	}

}
