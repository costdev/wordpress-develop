<?php
/**
 * Tests for the WP_Filesystem_Direct::getchmod() method.
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
 * @covers WP_Filesystem_Direct::getchmod
 */
class Tests_Getchmod extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::getchmod()` returns
	 * the permissions for a path.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 * @dataProvider data_paths_that_exist
	 *
	 * @param string $path The path.
	 */
	public function test_should_get_chmod( $path ) {
		$actual = self::$filesystem->getchmod( self::$file_structure['test_dir']['path'] . $path );
		$this->assertTrue( '' !== $actual );
	}

}
