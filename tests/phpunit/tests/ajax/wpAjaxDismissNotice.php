<?php
/**
 * Admin Ajax functions to be tested.
 */
require_once ABSPATH . 'wp-admin/includes/ajax-actions.php';

/**
 * Tests for wp_ajax_dismiss_notice().
 *
 * @group ajax
 *
 * @covers ::wp_ajax_dismiss_notice
 */
class Tests_Ajax_WpAjaxDismissNotice extends WP_Ajax_UnitTestCase {

	/**
	 * Tests that a notice is dismissed.
	 *
	 * @ticket
	 *
	 * @dataProvider data_should_dismiss_notice
	 *
	 * @param string     $slug       The slug of the notice.
	 * @param int|string $expiration Time until expiration in seconds. 0 = no expiration (permanent).
	 *                               'skip' to skip adding the 'expiration' request parameter.
	 */
	public function test_should_dismiss_notice( $slug, $expiration ) {
		// Become an administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_POST['nonce'] = wp_create_nonce( 'dismiss-notice' );
		$_POST['slug']  = $slug;

		if ( 'skip' !== $expiration ) {
			$_POST['expiration'] = $expiration;
		}

		// Make the request.
		try {
			$this->_handleAjax( 'dismiss-notice' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$this->assertTrue(
			$response['success'],
			'The notice was not dismissed.'
		);

		$this->assertSame(
			'The notice was successfully dismissed.',
			$response['data'],
			'An unexpected JSON success message was received.'
		);
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_dismiss_notice() {
		return array(
			'no expiration provided (should be permanent)' => array(
				'slug'       => 'mynotice-dismiss-forever-default',
				'expiration' => 'skip',
			),
			'an expiration of 0 seconds (permanent)'       => array(
				'slug'       => 'mynotice-dismiss-forever-specified',
				'expiration' => 0,
			),
			'an expiration of 30 days'                     => array(
				'slug'       => 'mynotice-dismiss-for-30-days',
				'expiration' => 30 * DAY_IN_SECONDS,
			),
			'a (bool) true slug' => array(
				'slug'       => true,
				'expiration' => 0,
				'expected'   => "Failed to dismiss notice: The notice's slug must not be an empty string.",
			),
			'an (int) 1 slug' => array(
				'slug'       => 1,
				'expiration' => 0,
			),
			'an (int) 0 slug' => array(
				'slug'       => 0,
				'expiration' => 0,
			),
			'a (float) 1.0 slug' => array(
				'slug'       => 1.0,
				'expiration' => 0,
			),
			'a (float) 0.0 slug' => array(
				'slug'       => 0.0,
				'expiration' => 0,
			),
			'a NULL "expiration" value' => array(
				'slug'       => 'mynotice',
				'expiration' => null,
			),
			'a (float) 1.0 "expiration" value' => array(
				'slug'       => 'mynotice',
				'expiration' => 1.0,
			),
			'a (float) 0.0 "expiration" value' => array(
				'slug'       => 'mynotice',
				'expiration' => 0.0,
			),
			'a (string) "1" "expiration" value'            => array(
				'slug'       => 'mynotice',
				'expiration' => '1',
			),
			'a (string) "0" "expiration" value'            => array(
				'slug'       => 'mynotice',
				'expiration' => '0',
			),
			'a NAN "expiration" value' => array(
				'slug'       => 'mynotice',
				'expiration' => NAN,
			),
			'an INF "expiration" value' => array(
				'slug'       => 'mynotice',
				'expiration' => INF,
			),
		);
	}

	/**
	 * Tests that a notice with an invalid slug is not dismissed.
	 *
	 * @ticket
	 *
	 * @dataProvider data_should_not_dismiss_notice_with_invalid_slug
	 *
	 * @param mixed      $slug       The slug of the notice.
	 *                               'skip' to skip adding the 'slug' request parameter.
	 * @param int|string $expiration Time until expiration in seconds. 0 = no expiration (permanent).
	 *                               'skip' to skip adding the 'expiration' request parameter.
	 * @param string     $expected   The expected JSON error.
	 */
	public function test_should_not_dismiss_notice_with_invalid_slug( $slug, $expiration, $expected ) {
		// Become an administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_POST['nonce'] = wp_create_nonce( 'dismiss-notice' );

		if ( 'skip' !== $slug ) {
			$_POST['slug'] = $slug;
		}

		if ( 'skip' !== $expiration ) {
			$_POST['expiration'] = $expiration;
		}

		// Make the request.
		try {
			$this->_handleAjax( 'dismiss-notice' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$this->assertFalse(
			$response['success'],
			'The notice was dismissed.'
		);

		$this->assertSame(
			$expected,
			$response['data'],
			'An unexpected JSON error message was received.'
		);
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_not_dismiss_notice_with_invalid_slug() {
		return array(
			'an empty "dismissible" array'                 => array(
				'slug'       => 'skip',
				'expiration' => 'skip',
				'expected'   => 'Failed to dismiss notice: The notice does not have a slug.',
			),
			'no "slug" key in the "dismissible" array'     => array(
				'slug'       => 'skip',
				'expiration' => 0,
				'expected'   => 'Failed to dismiss notice: The notice does not have a slug.',
			),
			'a NULL "slug" key in the "dismissible" array' => array(
				'slug'       => null,
				'expiration' => 0,
				'expected'   => 'Failed to dismiss notice: The notice does not have a slug.',
			),
			'a (bool) false "slug" key in the "dismissible" array' => array(
				'slug'       => false,
				'expiration' => 0,
				'expected'   => "Failed to dismiss notice: The notice's slug must not be an empty string.",
			),
			'an empty array "slug" key in the "dismissible" array' => array(
				'slug'       => array(),
				'expiration' => 0,
				'expected'   => "Failed to dismiss notice: The notice's slug must not be an empty string.",
			),
			'a populated array "slug" key in the "dismissible" array' => array(
				'slug'       => array( 'mynotice-dismiss-forever' ),
				'expiration' => 0,
				'expected'   => "Failed to dismiss notice: The notice's slug must not be an empty string.",
			),
			'an object "slug" key in the "dismissible" array' => array(
				'slug'       => new stdClass(),
				'expiration' => 0,
				'expected'   => "Failed to dismiss notice: The notice's slug must not be an empty string.",
			),
			'an empty string "slug" key in the "dismissible" array' => array(
				'slug'       => '',
				'expiration' => 0,
				'expected'   => "Failed to dismiss notice: The notice's slug must not be an empty string.",
			),
			'a "slug" key containing only space in the "dismissible" array' => array(
				'slug'       => " \r\t\n",
				'expiration' => 0,
				'expected'   => "Failed to dismiss notice: The notice's slug must not be an empty string.",
			),
		);
	}

	/**
	 * Tests that a notice with an invalid slug is not dismissed.
	 *
	 * @ticket
	 *
	 * @dataProvider data_should_not_dismiss_notice_with_invalid_expiration
	 *
	 * @param mixed  $expiration Time until expiration in seconds. 0 = no expiration (permanent).
	 * @param string $expected   The expected JSON error.
	 */
	public function test_should_not_dismiss_notice_with_invalid_expiration( $expiration, $expected ) {
		// Become an administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_POST['nonce']      = wp_create_nonce( 'dismiss-notice' );
		$_POST['slug']       = 'mynotice';
		$_POST['expiration'] = $expiration;

		// Make the request.
		try {
			$this->_handleAjax( 'dismiss-notice' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$this->assertFalse(
			$response['success'],
			'The notice was dismissed.'
		);

		$this->assertSame(
			$expected,
			$response['data'],
			'An unexpected JSON error message was received.'
		);
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_not_dismiss_notice_with_invalid_expiration() {
		return array(
			'a (bool) false "expiration" value'    => array(
				'expiration' => false,
				'expected'   => 'Failed to dismiss notice: The expiration time must be a number of seconds.',
			),
			'a (bool) true "expiration" value'     => array(
				'expiration' => true,
				'expected'   => 'Failed to dismiss notice: The expiration time must be a number of seconds.',
			),
			'an empty array "expiration" value'    => array(
				'expiration' => array(),
				'expected'   => 'Failed to dismiss notice: The expiration time must be a number of seconds.',
			),
			'a populated array "expiration" value' => array(
				'expiration' => array( 1 ),
				'expected'   => 'Failed to dismiss notice: The expiration time must be a number of seconds.',
			),
			'an object "expiration" value'         => array(
				'expiration' => new stdClass(),
				'expected'   => 'Failed to dismiss notice: The expiration time must be a number of seconds.',
			),
			'a (string) "-1" "expiration" value'   => array(
				'expiration' => '-1',
				'expected'   => 'Failed to dismiss notice: The expiration time must be greater than or equal to 0.',
			),
			'a negative "expiration" value'        => array(
				'expiration' => -1,
				'expected'   => 'Failed to dismiss notice: The expiration time must be greater than or equal to 0.',
			),
		);
	}

	/**
	 * Tests that a notice with an invalid nonce is not dismissed.
	 *
	 * @ticket
	 *
	 * @dataProvider data_should_not_dismiss_notice_with_invalid_nonce
	 *
	 * @param string $nonce The nonce for the request.
	 *                      'skip' to skip adding the request parameter.
	 */
	public function test_should_not_dismiss_notice_with_invalid_nonce( $nonce ) {
		// Become an administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		if ( 'skip' !== $nonce ) {
			$_POST['nonce'] = $nonce;
		}

		$_POST['slug']       = 'mynotice';
		$_POST['expiration'] = 0;

		// Make the request.
		$this->expectException( 'WPAjaxDieStopException', 'The request did not die.' );
		$this->expectExceptionMessage( '-1', 'An unexpected exception was thrown.' );
		$this->_handleAjax( 'dismiss-notice' );
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_not_dismiss_notice_with_invalid_nonce() {
		return array(
			'no nonce'                 => array(
				'nonce' => 'skip',
			),
			'nonce for another action' => array(
				'nonce' => wp_create_nonce( 'my-other-action' ),
			),
		);
	}

	/**
	 * Tests that a notice is not dismissed for a non-privileged user.
	 *
	 * @ticket
	 */
	public function test_should_not_dismiss_notice_for_a_non_privileged_user() {
		// Set up a default request.
		$_POST['nonce']      = wp_create_nonce( 'dismiss-notice' );
		$_POST['slug']       = 'mynotice';
		$_POST['expiration'] = 0;

		// Make the request.
		$this->expectException( 'WPAjaxDieContinueException', 'The request did not die and continue.' );
		$this->expectExceptionMessage( '', 'An unexpected exception was thrown.' );
		$this->_handleAjax( 'dismiss-notice' );
	}

	/**
	 * Tests that a notice is not dismissed that is already dismissed.
	 *
	 * @ticket
	 */
	public function test_should_not_dismiss_notice_that_is_already_dismissed() {
		// Dismiss the notice.
		set_site_transient( 'wp_admin_notice_dismissed_mynotice', 1 );

		// Set up a default request.
		$_POST['nonce']      = wp_create_nonce( 'dismiss-notice' );
		$_POST['slug']       = 'mynotice';
		$_POST['expiration'] = 0;

		// Make the request.
		try {
			$this->_handleAjax( 'dismiss-notice' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Get the response.
		$response = json_decode( $this->_last_response, true );

		$this->assertFalse(
			$response['success'],
			'The notice was dismissed.'
		);

		$this->assertSame(
			'Failed to dismiss notice: The notice could not be dismissed.',
			$response['data'],
			'An unexpected JSON error message was received.'
		);
	}
}
