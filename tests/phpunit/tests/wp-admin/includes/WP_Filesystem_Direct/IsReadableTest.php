<?php
/**
 * Tests for the WP_Filesystem_Direct::is_readable() method.
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
 * @covers WP_Filesystem_Direct::is_readable
 */
class Tests_IsReadable extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::is_readable()`
	 * returns the correct result for a path.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 * @dataProvider data_paths_that_exist
	 *
	 * @param string $path     The path.
	 * @param bool   $expected The expected result.
	 */
	public function test_should_determine_if_a_path_is_readable( $path, $expected ) {
		$this->assertSame( $expected, self::$filesystem->is_readable( self::$file_structure['test_dir']['path'] . $path ) );
	}

}
