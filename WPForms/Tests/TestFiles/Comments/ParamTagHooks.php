<?php

/**
 * Fire when request was sent successfully or not.
 *
 * @since 1.3.0
 *
 * @param object $response     Response data.
 * @param array  $request_args Request arguments.
 * @param array  $contact      GetResponse
 *                             contact data.
 * @param array  $connection   Connection data.
 * @param array  $args         Additional arguments.
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
	$this->connection(),
	$this->connection( $a, $b, $c ),
	[
		'form_data' => $this->form_data,
		'fields'    => $this->fields,
		'entry'     => $this->entry,
	],
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

/** This action is documented in some-class.php. */
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
