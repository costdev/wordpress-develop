<?php
/**
 * Tests for the WP_Filesystem_Direct::is_file() method.
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
 * @covers WP_Filesystem_Direct::is_file
 */
class Tests_IsFile extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::is_file()` returns the correct value
	 * when checking whether a path is a file.
	 *
	 * @dataProvider data_paths_that_exist
	 * @dataProvider data_paths_that_do_not_exist
	 *
	 * @param string $path     The path to check.
	 * @param bool   $expected The expected result.
	 * @param string $type     The type of resource. Accepts 'f' or 'd'.
	 *                         Used to invert $expected due to data provider setup.
	 */
	public function test_should_determine_if_a_path_is_a_file( $path, $expected, $type ) {
		/*
		 * Invert the data provider's $expected value for
		 * directories with empty paths, or with paths containing "exists".
		 */
		if ( 'd' === $type && ( '' === $path || str_contains( $path, 'exists' ) ) ) {
			$expected = ! $expected;
		}
		$this->assertSame( $expected, self::$filesystem->is_file( self::$file_structure['test_dir']['path'] . $path ) );
	}

}
