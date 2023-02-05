<?php
/**
 * Tests for the WP_Filesystem_Direct::__construct() method.
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
 * @covers WP_Filesystem_Direct::__construct
 */
class Tests_Construct extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that the $method and $errors properties are set upon
	 * the instantiation of a WP_Filesystem_Direct object.
	 */
	public function test_should_set_method_and_errors() {
		// For coverage reports, a new object must be created in the method.
		$filesystem = new \WP_Filesystem_Direct( null );

		$this->assertSame(
			'direct',
			$filesystem->method,
			'The "$method" property is not set to "direct".'
		);

		$this->assertInstanceOf(
			'WP_Error',
			$filesystem->errors,
			'The "$errors" property is not set to a WP_Error object.'
		);
	}

}
