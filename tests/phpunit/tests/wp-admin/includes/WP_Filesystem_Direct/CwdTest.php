<?php
/**
 * Tests for the WP_Filesystem_Direct::cwd() method.
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
 * @covers WP_Filesystem_Direct::cwd
 */
class Tests_Cwd extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::cwd()` returns the current
	 * working directory.
	 */
	public function test_should_get_current_working_directory() {
		$this->assertSame( wp_normalize_path( dirname( ABSPATH ) ), self::$filesystem->cwd() );
	}

}
