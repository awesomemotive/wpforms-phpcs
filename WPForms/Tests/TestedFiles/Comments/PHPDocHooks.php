<?php

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
 * @param object $response     Response data.
 * @param array  $request_args Request arguments.
 * @param array  $contact      GetResponse contact data.
 * @param array  $connection   Connection data.
 * @param array  $args         Additional arguments.
 *
 * @return Response
 */
$test = apply_filters(
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
 * @see This filter is documented in path/to/filename.php.
 */
$test = apply_filters(
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

$test = apply_filters(
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
