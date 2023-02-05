<?php
/**
 * Tests for the WP_Filesystem_Direct::atime() method.
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
 * @covers WP_Filesystem_Direct::atime
 */
class Tests_Atime extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::atime()`
	 * returns the correct result for a path.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 * @dataProvider data_paths_that_exist
	 *
	 * @param string $path     The path.
	 * @param bool   $expected The expected result.
	 */
	public function test_should_determine_file_accessed_time( $path, $expected ) {
		$result    = self::$filesystem->atime( self::$file_structure['test_dir']['path'] . $path );
		$has_atime = false !== $result;

		$this->assertSame(
			$expected,
			$has_atime,
			'The accessed time did not match the expected time.'
		);

		if ( $expected ) {
			$this->assertIsInt(
				$result,
				'The accessed time is not an integer.'
			);
		}
	}

}
