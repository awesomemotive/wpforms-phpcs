<?php

/**
 * Fire when request was sent successfully or not.
 *
 * @since 1.3.0
 *
 * @param object|string[] $response     Response data.
 * @param array           $request_args Request arguments.
 * @param array           $contact      GetResponse
 *                                  contact data.
 * @param array           $connection   Connection data.
 * @param array           $args         Additional arguments.
 */
do_action(
	'wpforms_getresponse_provider_process_task_async_action_subscribe_after',
	$response,
	$request_args,
	$contact,
	$this->connection,
	array(
		'form_data' => $this->form_data,
		'fields'    => $this->fields,
		'entry'     => $this->entry,
	)
);

/**
 * @see This action is documented in path/to/filename.php.
 */
do_action(
	'wpforms_getresponse_provider_process_task_async_action_subscribe_after',
	$response,
	$request_args,
	$contact,
	$this->connection,
	[
		'form_data' => $this->form_data,
		'fields'    => $this->fields,
		'entry'     => $this->entry,
	]
);

/**
 * Fire when request was sent successfully or not.
 *
 * @since 1.3.0
 *
 * @param object $response Response data.
 * @param array $request_args Request arguments.
 * @param array $contact GetResponse contact data.
 * @param array $connection Connection data.
 * @param array $args Additional arguments.
 */
do_action(
	'wpforms_getresponse_provider_process_task_async_action_subscribe_after',
	$response,
	$request_args,
	$contact,
	$this->connection,
	$this->connection[ $test ],
);

/**
 * Fire when request was sent successfully or not.
 *
 * @since 1.3.0
 *
 * @param object $response     Response data.
 * @param array  $request_args Request arguments.
 * @param array  $contact      GetResponse contact data.
 * @param array  $connection   Connection data.
 * @param array  $args         Additional arguments.
 * @param mixed  $arg6         Test argument.
 * @param mixed  $arg7         Test argument.
 * @param mixed  $arg8         Test argument.
 * @param mixed  $arg9         Test argument.
 * @param mixed  $arg10        Test argument.
 * @param mixed  $arg11        Test argument.
 * @param mixed  $arg12        Test argument.
 * @param mixed  $arg13        Test argument.
 */
do_action(
	'wpforms_getresponse_provider_process_task_async_action_subscribe_after',
	$response,
	$request_args,
	$contact,
	$this->connection,
	[
		'form_data' => $this->form_data,
		'fields'    => $this->fields,
		'entry'     => $this->entry,
	],
	$this->connection[ $test ],
	$this->connection(),
	$this->connection( $a, $b, $c ),
	function () {
		return 'true';
	},
	Static::CONST,
	Static::CONST[ $test ],
	Static::method(),
	Static::method( $a, $b, $c ),
);

/**
 * Fire when request was sent successfully or not.
 *
 * @since 1.3.0
 *
 * @param
 * @param array
 * @param       $contact
 * @param Connection data.
 * @param array $args
 * @param array  Connection data.
 * @param $args  Connection data.
 *
 * @return Response
 */
$test = apply_filters(
	'wpforms_getresponse_provider_process_task_async_action_subscribe_after',
	$response,
	$response,
	$response,
	$request_args,
	$contact,
	$this->connection,
	[
		'form_data' => $this->form_data,
		'fields'    => $this->fields,
		'entry'     => $this->entry,
	]
);

/**
 * Fire when request was sent successfully or not.
 *
 * @since 1.3.0
 *
 * @param object $response     Response data
 * @param array  $request_args Request arguments
 * @param array  $contact      GetResponse
 *                             contact data.
 * @param array  $connection   Connection
 *                             data
 * @param array  $args         Additional
 *                             arguments.
 */
do_action(
	'wpforms_getresponse_provider_process_task_async_action_subscribe_after',
	$response,
	$request_args,
	$contact,
	$this->connection,
	[
		'form_data' => $this->form_data,
		'fields'    => $this->fields,
		'entry'     => $this->entry,
	]
);

/**
 * Fire when request was sent successfully or not.
 *
 * @since 1.3.0
 *
 * @param  object $response     Response data.
 * @param  array  $request_args Request arguments.
 * @param  array  $contact      GetResponse contact data.
 * @param  array  $connection   Connection data.
 * @param  array  $args         Additional arguments.
 */
do_action(
	'wpforms_getresponse_provider_process_task_async_action_subscribe_after',
	$response,
	$request_args,
	$contact,
	$this->connection,
	[
		'form_data' => $this->form_data,
		'fields'    => $this->fields,
		'entry'     => $this->entry,
	]
);

// The next case generated the endless loop in the \WPForms\Sniffs\Comments\ParamTagHooksSniff::countArguments.

/** This action is documented in some-class.php */
do_action( 'wpforms_display_submit_after', $this->displaysubmit_after_action );

// This case generated 'You should have 39 @param tags' (39 as an example).
do_action( 'hook_without_args' );

// This case generated 'You should have 16 @param tags'.
$text = (string) apply_filters(
	'wpforms_frontend_shortcode_amp_text',
	sprintf( /* translators: %s - URL to a non-amp version of a page with the form. */
		__( '<a href="%s">Go to the full page</a> to view and submit the form.', 'wpforms-lite' ),
		esc_url( 'some_url' )
	),
	555,
	'http://site.org/cool-page',
	[]
);

// These actions/filters should not produce an error.

/** This action is documented in includes/class-frontend.php */
do_action( 'wpforms_display_submit_after', $this->form_data );

/** This filter is documented in wp-includes/post-template.php */
$content = apply_filters( 'the_content', $content );

// This filter counted args improperly due to presence of the function with parenthesis.

/**
 * Allow modifying the text or url for the full page on the AMP pages.
 *
 * @since 1.4.1.1
 * @since 1.7.1 Added $form_id, $full_page_url, and $form_data arguments.
 *
 * @param int   $form_id   Form id.
 * @param array $form_data Form data and settings.
 * @param array $form_data Form data and settings.
 * @param array $form_data Form data and settings.
 *
 * @return string
 */
$text = (string) apply_filters(
	'wpforms_frontend_shortcode_amp_text',
	sprintf( /* translators: %s - URL to a non-amp version of a page with the form. */
		__( '<a href="%s">Go to the full page</a> to view and submit the form.', 'wpforms-lite' ),
		esc_url( $full_page_url )
	),
	$form_id,
	$full_page_url,
	$form_data
);

// This filter counted args improperly due to expression in the argument.

/**
 * Allow filtering Rich Text field media cleanup window time.
 *
 * @since 1.7.0
 *
 * @param int $time Time.
 */
$time = (int) apply_filters( 'wpforms_richtext_override_auth_for_ajax_media_calls_time', time() + 1 * DAY_IN_SECONDS );
