<?php
/**
 * Tests for the WP_Filesystem_Direct::put_contents() method.
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
 * @covers WP_Filesystem_Direct::put_contents
 */
class Tests_PutContents extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::put_contents()`
	 * returns false for a directory.
	 */
	public function test_should_return_false_for_a_directory() {
		$this->assertFalse( self::$filesystem->put_contents( self::$file_structure['test_dir']['path'], 'New content.' ) );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::put_contents()` inserts
	 * content into the provided file.
	 */
	public function test_should_insert_contents_into_file() {
		$file   = self::$file_structure['test_dir']['path'] . 'file-to-create.txt';
		$actual = self::$filesystem->put_contents( $file, 'New content.', 0644 );

		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		@unlink( $file );

		$this->assertTrue( $actual, 'The contents were not inserted.' );
	}

}
