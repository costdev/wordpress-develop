<?php

/**
 * Tests for `wp_get_admin_notice()`.
 *
 * @group functions
 *
 * @covers ::wp_get_admin_notice
 */
class Tests_Functions_WpGetAdminNotice extends WP_UnitTestCase {

	/**
	 * Tests that `wp_get_admin_notice()` returns the expected admin notice markup.
	 *
	 * @ticket 57791
	 *
	 * @dataProvider data_should_return_admin_notice
	 *
	 * @param string $message  The message.
	 * @param array  $args     Arguments for the admin notice.
	 * @param string $expected The expected admin notice markup.
	 */
	public function test_should_return_admin_notice( $message, $args, $expected ) {
		$this->assertSame( $expected, wp_get_admin_notice( $message, $args ) );
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_return_admin_notice() {
		return array(
			'defaults'                                  => array(
				'message'  => 'A notice with defaults.',
				'args'     => array(),
				'expected' => '<div class="notice"><p>A notice with defaults.</p></div>',
			),
			'an empty message (used for templates)'     => array(
				'message'  => '',
				'args'     => array(
					'type'               => 'error',
					'dismissible'        => true,
					'id'                 => 'message',
					'additional_classes' => array( 'inline', 'hidden' ),
				),
				'expected' => '<div id="message" class="notice notice-error is-dismissible inline hidden"><p></p></div>',
			),
			'an empty message (used for templates) without paragraph wrapping' => array(
				'message'  => '',
				'args'     => array(
					'type'               => 'error',
					'dismissible'        => true,
					'id'                 => 'message',
					'additional_classes' => array( 'inline', 'hidden' ),
					'paragraph_wrap'     => false,
				),
				'expected' => '<div id="message" class="notice notice-error is-dismissible inline hidden"></div>',
			),
			'an "error" notice'                         => array(
				'message'  => 'An "error" notice.',
				'args'     => array(
					'type' => 'error',
				),
				'expected' => '<div class="notice notice-error"><p>An "error" notice.</p></div>',
			),
			'a "success" notice'                        => array(
				'message'  => 'A "success" notice.',
				'args'     => array(
					'type' => 'success',
				),
				'expected' => '<div class="notice notice-success"><p>A "success" notice.</p></div>',
			),
			'a "warning" notice'                        => array(
				'message'  => 'A "warning" notice.',
				'args'     => array(
					'type' => 'warning',
				),
				'expected' => '<div class="notice notice-warning"><p>A "warning" notice.</p></div>',
			),
			'an "info" notice'                          => array(
				'message'  => 'An "info" notice.',
				'args'     => array(
					'type' => 'info',
				),
				'expected' => '<div class="notice notice-info"><p>An "info" notice.</p></div>',
			),
			'a type that already starts with "notice-"' => array(
				'message'  => 'A type that already starts with "notice-".',
				'args'     => array(
					'type' => 'notice-info',
				),
				'expected' => '<div class="notice notice-notice-info"><p>A type that already starts with "notice-".</p></div>',
			),
			'a dismissible notice'                      => array(
				'message'  => 'A dismissible notice.',
				'args'     => array(
					'dismissible' => true,
				),
				'expected' => '<div class="notice is-dismissible"><p>A dismissible notice.</p></div>',
			),
			'no type and an ID'                         => array(
				'message'  => 'A notice with an ID.',
				'args'     => array(
					'id' => 'message',
				),
				'expected' => '<div id="message" class="notice"><p>A notice with an ID.</p></div>',
			),
			'a type and an ID'                          => array(
				'message'  => 'A warning notice with an ID.',
				'args'     => array(
					'type' => 'warning',
					'id'   => 'message',
				),
				'expected' => '<div id="message" class="notice notice-warning"><p>A warning notice with an ID.</p></div>',
			),
			'no type and additional classes'            => array(
				'message'  => 'A notice with additional classes.',
				'args'     => array(
					'additional_classes' => array( 'error', 'notice-alt' ),
				),
				'expected' => '<div class="notice error notice-alt"><p>A notice with additional classes.</p></div>',
			),
			'a type and additional classes'             => array(
				'message'  => 'A warning notice with additional classes.',
				'args'     => array(
					'type'               => 'warning',
					'additional_classes' => array( 'error', 'notice-alt' ),
				),
				'expected' => '<div class="notice notice-warning error notice-alt"><p>A warning notice with additional classes.</p></div>',
			),
			'a dismissible notice with a type and additional classes' => array(
				'message'  => 'A dismissible warning notice with a type and additional classes.',
				'args'     => array(
					'type'               => 'warning',
					'dismissible'        => true,
					'additional_classes' => array( 'error', 'notice-alt' ),
				),
				'expected' => '<div class="notice notice-warning is-dismissible error notice-alt"><p>A dismissible warning notice with a type and additional classes.</p></div>',
			),
			'a notice without paragraph wrapping'       => array(
				'message'  => '<span>A notice without paragraph wrapping.</span>',
				'args'     => array(
					'paragraph_wrap' => false,
				),
				'expected' => '<div class="notice"><span>A notice without paragraph wrapping.</span></div>',
			),
			'an unsafe type'                            => array(
				'message'  => 'A notice with an unsafe type.',
				'args'     => array(
					'type' => '"><script>alert("Howdy,admin!");</script>',
				),
				'expected' => '<div class="notice notice-"><script>alert("Howdy,admin!");</script>"><p>A notice with an unsafe type.</p></div>',
			),
			'an unsafe ID'                              => array(
				'message'  => 'A notice with an unsafe ID.',
				'args'     => array(
					'id' => '"><script>alert( "Howdy, admin!" );</script> <div class="notice',
				),
				'expected' => '<div id=""><script>alert( "Howdy, admin!" );</script> <div class="notice" class="notice"><p>A notice with an unsafe ID.</p></div>',
			),
			'unsafe additional classes'                 => array(
				'message'  => 'A notice with unsafe additional classes.',
				'args'     => array(
					'additional_classes' => array( '"><script>alert( "Howdy, admin!" );</script> <div class="notice' ),
				),
				'expected' => '<div class="notice "><script>alert( "Howdy, admin!" );</script> <div class="notice"><p>A notice with unsafe additional classes.</p></div>',
			),
			'a type that is not a string'               => array(
				'message'  => 'A notice with a type that is not a string.',
				'args'     => array(
					'type' => array(),
				),
				'expected' => '<div class="notice"><p>A notice with a type that is not a string.</p></div>',
			),
			'a type with only empty space'              => array(
				'message'  => 'A notice with a type with only empty space.',
				'args'     => array(
					'type' => " \t\r\n",
				),
				'expected' => '<div class="notice"><p>A notice with a type with only empty space.</p></div>',
			),
			'an ID that is not a string'                => array(
				'message'  => 'A notice with an ID that is not a string.',
				'args'     => array(
					'id' => array( 'message' ),
				),
				'expected' => '<div class="notice"><p>A notice with an ID that is not a string.</p></div>',
			),
			'an ID with only empty space'               => array(
				'message'  => 'A notice with an ID with only empty space.',
				'args'     => array(
					'id' => " \t\r\n",
				),
				'expected' => '<div class="notice"><p>A notice with an ID with only empty space.</p></div>',
			),
			'dismissible as a truthy value rather than (bool) true' => array(
				'message'  => 'A notice with dismissible as a truthy value rather than (bool) true.',
				'args'     => array(
					'dismissible' => 1,
				),
				'expected' => '<div class="notice"><p>A notice with dismissible as a truthy value rather than (bool) true.</p></div>',
			),
			'additional classes that are not an array'  => array(
				'message'  => 'A notice with additional classes that are not an array.',
				'args'     => array(
					'additional_classes' => 'class-1 class-2 class-3',
				),
				'expected' => '<div class="notice"><p>A notice with additional classes that are not an array.</p></div>',
			),
			'additional attribute with a value'         => array(
				'message'  => 'A notice with an additional attribute with a value.',
				'args'     => array(
					'attributes' => array( 'aria-live' => 'assertive' ),
				),
				'expected' => '<div class="notice" aria-live="assertive"><p>A notice with an additional attribute with a value.</p></div>',
			),
			'additional hidden attribute'               => array(
				'message'  => 'A notice with the hidden attribute.',
				'args'     => array(
					'attributes' => array( 'hidden' => true ),
				),
				'expected' => '<div class="notice" hidden><p>A notice with the hidden attribute.</p></div>',
			),
			'additional attribute no associative keys'  => array(
				'message'  => 'A notice with a boolean attribute without an associative key.',
				'args'     => array(
					'attributes' => array( 'hidden' ),
				),
				'expected' => '<div class="notice" hidden><p>A notice with a boolean attribute without an associative key.</p></div>',
			),
			'additional attribute with role'            => array(
				'message'  => 'A notice with an additional attribute role.',
				'args'     => array(
					'attributes' => array( 'role' => 'alert' ),
				),
				'expected' => '<div class="notice" role="alert"><p>A notice with an additional attribute role.</p></div>',
			),
			'multiple additional attributes'            => array(
				'message'  => 'A notice with multiple additional attributes.',
				'args'     => array(
					'attributes' => array(
						'role'      => 'alert',
						'data-test' => -1,
					),
				),
				'expected' => '<div class="notice" role="alert" data-test="-1"><p>A notice with multiple additional attributes.</p></div>',
			),
			'data attribute with unsafe value'          => array(
				'message'  => 'A notice with an additional attribute with an unsafe value.',
				'args'     => array(
					'attributes' => array( 'data-unsafe' => '<script>alert( "Howdy, admin!" );</script>' ),
				),
				'expected' => '<div class="notice" data-unsafe="&lt;script&gt;alert( &quot;Howdy, admin!&quot; );&lt;/script&gt;"><p>A notice with an additional attribute with an unsafe value.</p></div>',
			),
			'multiple attributes with "role", invalid, data-*, numeric, and boolean' => array(
				'message'  => 'A notice with multiple attributes with "role", invalid, "data-*", numeric, and boolean.',
				'args'     => array(
					'attributes' => array(
						'role'      => 'alert',
						'disabled'  => 'disabled',
						'data-name' => 'my-name',
						'data-id'   => 1,
						'hidden',
					),
				),
				'expected' => '<div class="notice" role="alert" disabled="disabled" data-name="my-name" data-id="1" hidden><p>A notice with multiple attributes with "role", invalid, "data-*", numeric, and boolean.</p></div>',
			),
			'paragraph wrapping as a falsy value rather than (bool) false' => array(
				'message'  => 'A notice with paragraph wrapping as a falsy value rather than (bool) false.',
				'args'     => array(
					'paragraph_wrap' => 0,
				),
				'expected' => '<div class="notice"><p>A notice with paragraph wrapping as a falsy value rather than (bool) false.</p></div>',
			),
			'a notice that should be dismissed permanently' => array(
				'message'  => 'A notice that should be dismissed permanently.',
				'args'     => array(
					'dismissible' => array(
						'slug' => 'mynotice-dismiss-permanently',
					),
				),
				'expected' => '<div class="notice is-dismissible" data-slug="mynotice-dismiss-permanently"><p>A notice that should be dismissed permanently.</p></div>',
			),
			'a notice that should be dismissed for 30 days' => array(
				'message'  => 'A notice that should be dismissed for 30 days.',
				'args'     => array(
					'dismissible' => array(
						'slug'       => 'mynotice-dismiss-for-30-days',
						'expiration' => 30 * DAY_IN_SECONDS,
					),
				),
				'expected' => '<div class="notice is-dismissible" data-slug="mynotice-dismiss-for-30-days" data-expiration="2592000"><p>A notice that should be dismissed for 30 days.</p></div>',
			),
		);
	}

	/**
	 * Tests that `wp_get_admin_notice()` throws a `_doing_it_wrong()` when
	 * a 'type' containing spaces is passed.
	 *
	 * @ticket 57791
	 *
	 * @expectedIncorrectUsage wp_get_admin_notice
	 */
	public function test_should_throw_doing_it_wrong_with_a_type_containing_spaces() {
		$this->assertSame(
			'<div class="notice notice-first second third fourth"><p>A type containing spaces.</p></div>',
			wp_get_admin_notice(
				'A type containing spaces.',
				array( 'type' => 'first second third fourth' )
			)
		);
	}

	/**
	 * Tests that `wp_get_admin_notice()` applies filters.
	 *
	 * @ticket 57791
	 *
	 * @dataProvider data_should_apply_filters
	 *
	 * @param string $hook_name The name of the filter hook.
	 */
	public function test_should_apply_filters( $hook_name ) {
		$filter = new MockAction();
		add_filter( $hook_name, array( $filter, 'filter' ) );

		wp_get_admin_notice( 'A notice.', array( 'type' => 'success' ) );

		$this->assertSame( 1, $filter->get_call_count() );
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_apply_filters() {
		return array(
			'wp_admin_notice_args'   => array( 'hook_name' => 'wp_admin_notice_args' ),
			'wp_admin_notice_markup' => array( 'hook_name' => 'wp_admin_notice_markup' ),
		);
	}

	/**
	 * Tests that `wp_get_admin_notice()` returns an empty string for a notice that is still dismissed.
	 *
	 * @ticket
	 *
	 * @dataProvider data_notices_with_dismissible_array
	 *
	 * @param array $dismissible The value for the dismissible array.
	 */
	public function test_should_return_empty_string_for_a_notice_that_is_still_dismissed( $dismissible ) {
		// The notice is still dismissed.
		set_site_transient( 'wp_admin_notice_dismissed_' . $dismissible['slug'], 1 );

		$this->assertSame(
			'',
			wp_get_admin_notice(
				'A notice that is still dismissed.',
				array(
					'dismissible' => $dismissible,
				)
			)
		);
	}

	/**
	 * Tests that `wp_get_admin_notice()` does not apply markup filters for a notice that is still dismissed.
	 *
	 * @ticket
	 *
	 * @dataProvider data_notices_with_dismissible_array
	 *
	 * @param array $dismissible The value for the dismissible array.
	 */
	public function test_should_not_apply_markup_filters_for_a_notice_that_is_still_dismissed( $dismissible ) {
		$filter = new MockAction();
		add_filter( 'wp_admin_notice_markup', array( $filter, 'filter' ) );

		// The notice is still dismissed.
		set_site_transient( 'wp_admin_notice_dismissed_' . $dismissible['slug'], 1 );

		wp_get_admin_notice(
			'A notice that is still dismissed.',
			array(
				'dismissible' => $dismissible,
			)
		);

		$this->assertSame( 0, $filter->get_call_count() );
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_notices_with_dismissible_array() {
		return array(
			'a permanently dismissed notice (slug only, no expiration provided)' => array(
				'dismissible' => array(
					'slug' => 'mynotice-dismiss-forever',
				),
			),
			'a notice dismissed for 30 days' => array(
				'dismissible' => array(
					'slug'       => 'mynotice-dismiss-for-30-days',
					'expiration' => 30 * DAY_IN_SECONDS,
				),
			),
		);
	}

	/**
	 * Tests that `wp_get_admin_notice()` triggers an error.
	 *
	 * @ticket
	 *
	 * @dataProvider data_should_trigger_error_for_an_invalid_dismissible_slug
	 * @dataProvider data_should_trigger_error_for_invalid_dismissible_expiration
	 *
	 * @param string $message         The message.
	 * @param array  $args            Arguments for the admin notice.
	 * @param string $expected_markup The expected admin notice markup.
	 * @param string $expected_error  The expected error message.
	 */
	public function test_should_trigger_error( $message, $args, $expected_markup, $expected_error ) {
		// Ensure no previous errors exist.
		error_clear_last();

		// Backup the error reporting value.
		$original_error_reporting = error_reporting();

		// Suppress E_USER_NOTICE.
		error_reporting( E_ALL & ~E_USER_NOTICE );

		$actual     = wp_get_admin_notice( $message, $args );
		$last_error = error_get_last();

		// Reset error reporting.
		error_reporting( $original_error_reporting );

		$this->assertSame( $expected_markup, $actual );
		$this->assertIsArray( $last_error, 'An error was not triggered.' );
		$this->assertSame( E_USER_NOTICE, $last_error['type'], 'The error was not a notice.' );
		$this->assertSame( $last_error['message'], 'wp_get_admin_notice(): ' . $expected_error, 'The wrong error message was sent.' );
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_trigger_error_for_an_invalid_dismissible_slug() {
		return array(
			'an empty "dismissible" array'                 => array(
				'message'         => 'an empty "dismissible" array',
				'args'            => array(
					'dismissible' => array(),
				),
				'expected_markup' => '<div class="notice"><p>an empty "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'no "slug" key in the "dismissible" array'     => array(
				'message'         => 'no "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'expiration' => 30 * DAY_IN_SECONDS ),
				),
				'expected_markup' => '<div class="notice"><p>no "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'a NULL "slug" key in the "dismissible" array' => array(
				'message'         => 'a NULL "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => null ),
				),
				'expected_markup' => '<div class="notice"><p>a NULL "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'a (bool) false "slug" key in the "dismissible" array' => array(
				'message'         => 'a (bool) false "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => false ),
				),
				'expected_markup' => '<div class="notice"><p>a (bool) false "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'a (bool) true "slug" key in the "dismissible" array' => array(
				'message'         => 'a (bool) true "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => true ),
				),
				'expected_markup' => '<div class="notice"><p>a (bool) true "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'an integer "slug" key in the "dismissible" array' => array(
				'message'         => 'an integer "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => 1234 ),
				),
				'expected_markup' => '<div class="notice"><p>an integer "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'a float "slug" key in the "dismissible" array' => array(
				'message'         => 'a float "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => 12.34 ),
				),
				'expected_markup' => '<div class="notice"><p>a float "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'an empty array "slug" key in the "dismissible" array' => array(
				'message'         => 'an empty array "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => array() ),
				),
				'expected_markup' => '<div class="notice"><p>an empty array "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'a populated array "slug" key in the "dismissible" array' => array(
				'message'         => 'a populated array "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => array( 'mynotice-dismiss-forever' ) ),
				),
				'expected_markup' => '<div class="notice"><p>a populated array "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'an object "slug" key in the "dismissible" array' => array(
				'message'         => 'an object "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => new stdClass() ),
				),
				'expected_markup' => '<div class="notice"><p>an object "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a string.',
			),
			'an empty string "slug" key in the "dismissible" array' => array(
				'message'         => 'an empty string "slug" key in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => '' ),
				),
				'expected_markup' => '<div class="notice"><p>an empty string "slug" key in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a non-empty string.',
			),
			'a "slug" key containing only space in the "dismissible" array' => array(
				'message'         => 'a "slug" key containing only space in the "dismissible" array',
				'args'            => array(
					'dismissible' => array( 'slug' => " \r\t\n" ),
				),
				'expected_markup' => '<div class="notice"><p>a "slug" key containing only space in the "dismissible" array</p></div>',
				'expected_error'  => 'The "slug" key in the "dismissible" array must be a non-empty string.',
			),
		);
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_trigger_error_for_invalid_dismissible_expiration() {
		return array(
			'a NULL "expiration" value in the "dismissible" array' => array(
				'message'         => 'a null "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-null-expiration',
						'expiration' => null,
					),
				),
				'expected_markup' => '<div class="notice"><p>a null "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'a (bool) false "expiration" value in the "dismissible" array' => array(
				'message'         => 'a (bool) false "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-false-expiration',
						'expiration' => false,
					),
				),
				'expected_markup' => '<div class="notice"><p>a (bool) false "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'a (bool) true "expiration" value in the "dismissible" array' => array(
				'message'         => 'a (bool) true "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-true-expiration',
						'expiration' => true,
					),
				),
				'expected_markup' => '<div class="notice"><p>a (bool) true "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'an empty array "expiration" value in the "dismissible" array' => array(
				'message'         => 'an empty array "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-empty-array-expiration',
						'expiration' => array(),
					),
				),
				'expected_markup' => '<div class="notice"><p>an empty array "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'a populated array "expiration" value in the "dismissible" array' => array(
				'message'         => 'a populated array "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-populated-array-expiration',
						'expiration' => array( 30 * DAY_IN_SECONDS ),
					),
				),
				'expected_markup' => '<div class="notice"><p>a populated array "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'an object "expiration" value in the "dismissible" array' => array(
				'message'         => 'an object "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-object-expiration',
						'expiration' => array( 30 * DAY_IN_SECONDS ),
					),
				),
				'expected_markup' => '<div class="notice"><p>an object "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'a float "expiration" value in the "dismissible" array' => array(
				'message'         => 'a float "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-float-expiration',
						'expiration' => 30.0,
					),
				),
				'expected_markup' => '<div class="notice"><p>a float "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'a numeric string "expiration" value in the "dismissible" array' => array(
				'message'         => 'a numeric string "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-numeric-string-expiration',
						'expiration' => '30',
					),
				),
				'expected_markup' => '<div class="notice"><p>a numeric string "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'a NAN "expiration" value in the "dismissible" array' => array(
				'message'         => 'a NAN "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-nan-expiration',
						'expiration' => NAN,
					),
				),
				'expected_markup' => '<div class="notice"><p>a NAN "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'an INF "expiration" value in the "dismissible" array' => array(
				'message'         => 'an INF "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-inf-expiration',
						'expiration' => INF,
					),
				),
				'expected_markup' => '<div class="notice"><p>an INF "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be an integer.',
			),
			'a negative "expiration" value in the "dismissible" array' => array(
				'message'         => 'a negative "expiration" value in the "dismissible" array',
				'args'            => array(
					'dismissible' => array(
						'slug'       => 'mynotice-negative-expiration',
						'expiration' => -1,
					),
				),
				'expected_markup' => '<div class="notice"><p>a negative "expiration" value in the "dismissible" array</p></div>',
				'expected_error'  => 'The "expiration" key in the "dismissible" array must be greater than or equal to 0.',
			),
		);
	}
}
