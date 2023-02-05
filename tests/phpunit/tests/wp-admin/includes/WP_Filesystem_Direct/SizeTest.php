<?php
/**
 * Tests for the WP_Filesystem_Direct::size() method.
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
 * @covers WP_Filesystem_Direct::size
 */
class Tests_Size extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::size()`
	 * returns the correct result for a path.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 * @dataProvider data_paths_that_exist
	 *
	 * @param string $path     The path.
	 * @param bool   $expected The expected result.
	 */
	public function test_should_determine_file_size( $path, $expected ) {
		$result       = self::$filesystem->size( self::$file_structure['test_dir']['path'] . $path );
		$has_filesize = false !== $result;

		$this->assertSame( $expected, $has_filesize, 'The result is not the same.' );

		if ( $expected ) {
			$this->assertIsInt( $result, 'The mtime is not an integer.' );
		}
	}

}
