<?php
/**
 * Valid function.
 *
 * @since 1.2.0
 *
 * @return int
 */
function valid() {

	$vars = [ 1 ];

	foreach ( $vars as $var ) {
		return $var;
	}
}

/**
 * Valid function.
 *
 * @since 1.2.0
 *
 * @return array
 */
function valid2() {

	$vars = [ 1 ];
	$vars = array_merge( $vars, [ 2 ] );

	foreach ( $vars as $var ) {
		return $var;
	}

	return $vars;
}

/**
 * Valid function.
 *
 * @since 1.2.0
 */
function valid3() {

	$vars = [ 1 ];
	$vars = array_merge( $vars, [ 2 ] );
}

$details = apply_filters(
	'wpforms_authorize_net_process_update_card_field_value',
	$details,
	wpforms_authorize_net()->api->response->get_transaction_response()
);

if ( $attempts === false ) {
	$attempts = 1;
} else {
	$attempts++;
}

for ( $i = gmdate( 'y' ); $i < 50; $i++ ) {
	printf( '<option value="%d">%d</option>', absint( $i ), absint( $i ) );
}

/**
 * Valid function.
 *
 * @since 1.2.0
 *
 * @param string $arg Argument 1.
 */
function valid4( $arg = 'string' ) {
}

// phpcs:enable SomeRules.
$var = 1;
$var = 1;
$var = 1;
// phpcs:disable SomeRules.

$doc_link = sprintf(
	wp_kses(
	/* translators: %s - WPForms.com docs URL. */
		__( 'For more information <a href="%s" target="_blank" rel="noopener noreferrer">see our documentation</a>.', '.packages' ),
		[
			'a' => [
				'href'   => [],
				'target' => [],
				'rel'    => [],
			],
		]
	),
	'https://wpforms.com/docs/how-to-create-a-custom-form-template/'
);
?>

<?php
/**
 * Invalid function.
 *
 * @since 1.2.0
 *
 * @return int
 */
function invalid() {

	$vars = [ 1 ];
	foreach ( $vars as $var ) {
		return $var;
	}
}

/**
 * Invalid function.
 *
 * @since 1.2.0
 *
 * @return int
 */
function invalid2() {

	$vars = [ 1 ];
	$vars = array_merge( $vars, [ 2 ] );
	foreach ( $vars as $var ) {
		return $var;
	}
}

$args = [
	'markup' => 'open',
];
$this->field_option( 'basic-options', $field, $args );

// Skip invalid logs types.
$log_types = \WPForms\Logger\Log::get_log_types();
foreach ( $types as $key => $t ) {
	if ( ! isset( $log_types[ $t ] ) ) {
		unset( $types[ $key ] );
	}
}
