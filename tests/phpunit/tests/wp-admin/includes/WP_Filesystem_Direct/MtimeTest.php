<?php
/**
 * Tests for the WP_Filesystem_Direct::mtime() method.
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
 * @covers WP_Filesystem_Direct::mtime
 */
class Tests_Mtime extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::mtime()`
	 * returns the correct result for a path.
	 *
	 * @dataProvider data_paths_that_do_not_exist
	 * @dataProvider data_paths_that_exist
	 *
	 * @param string $path     The path.
	 * @param bool   $expected The expected result.
	 */
	public function test_should_determine_file_modified_time( $path, $expected ) {
		$result    = self::$filesystem->mtime( self::$file_structure['test_dir']['path'] . $path );
		$has_mtime = false !== $result;

		$this->assertSame(
			$expected,
			$has_mtime,
			'The result is not the same.'
		);

		if ( $expected ) {
			$this->assertIsInt(
				$result,
				'The mtime is not an integer.'
			);
		}
	}

}
