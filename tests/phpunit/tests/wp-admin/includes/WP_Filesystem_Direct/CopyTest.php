<?php
/**
 * Tests for the WP_Filesystem_Direct::copy() method.
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
 * @covers WP_Filesystem_Direct::copy
 */
class Tests_Copy extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::copy()` overwrites an existing
	 * destination when overwriting is enabled.
	 */
	public function test_should_overwrite_an_existing_file_when_overwriting_is_enabled() {
		$source      = self::$file_structure['visible_file']['path'];
		$destination = self::$file_structure['test_dir']['path'] . 'a_file_that_exists.dest';

		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		if ( ! @file_exists( $destination ) ) {
			@touch( $destination );
		}
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged

		$actual = self::$filesystem->copy( $source, $destination, true );

		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		@unlink( $destination );

		$this->assertTrue( $actual );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::copy()` does not overwrite
	 * an existing destination when overwriting is disabled.
	 */
	public function test_should_not_overwrite_an_existing_file_when_overwriting_is_disabled() {
		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged

		$source      = self::$file_structure['test_dir']['path'] . 'a_file_that_exists.txt';
		$destination = self::$file_structure['test_dir']['path'] . 'a_file_that_exists.dest';

		if ( ! @file_exists( $destination ) ) {
			@touch( $destination );
		}

		$actual = self::$filesystem->copy( $source, $destination );

		@unlink( $destination );

		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged
		$this->assertFalse( $actual );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::copy()` does not overwrite an existing
	 * destination when overwriting is enabled and the source and destination
	 * are the same.
	 */
	public function test_should_not_overwrite_when_overwriting_is_enabled_and_source_and_destination_are_the_same() {
		$source = self::$file_structure['test_dir']['path'] . 'a_file_that_exists.txt';
		$this->assertFalse( self::$filesystem->copy( $source, $source, true ) );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::copy()` sets file permissions after copying.
	 */
	public function test_should_set_chmod_after_copying() {
		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged

		$source      = self::$file_structure['test_dir']['path'] . 'a_file_that_exists.txt';
		$destination = self::$file_structure['test_dir']['path'] . 'a_file_that_exists.dest';

		if ( ! @file_exists( $destination ) ) {
			@touch( $destination );
		}

		$permissions          = substr( sprintf( '%o', @fileperms( $destination ) ), -4 );
		$expected_permissions = '0777' === $permissions ? 0755 : 0777;

		self::$filesystem->copy( $source, $destination, true, $expected_permissions );

		$actual = substr( sprintf( '%o', fileperms( $destination ) ), -4 );
		@unlink( $destination );

		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged
		$this->assertSame( '0777', $actual );
	}

}
