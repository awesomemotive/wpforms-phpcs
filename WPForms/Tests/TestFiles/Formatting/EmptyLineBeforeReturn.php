<?php
/**
 * Valid function.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function valid_function() {

	return true;
}

/**
 * Valid function.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function valid_function_2() {

	$var = true;

	return $var;
}

/**
 * Valid function.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function valid_function_3() {

	if ( true ) {
		return true;
	}

	if ( true ) {
		$krya = true;

		return true;
	}

	while ( true ) {
		echo 'null';

		return true;
	}

	while ( true ) {
		return true;
	}

	while ( $b ) {
		// Comment line.
		// Comment line 2.
		/**
		 * Some more comments.
		 */
		return true;
	}

	return true;
}

/**
 * Class Valid.
 *
 * @since 1.0.0
 */
class Valid {

	/**
	 * Valid function 4.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function valid_function_4() {

		return [
			'amount'   => $amount,
			'currency' => strtolower( wpforms_setting( 'currency', 'USD' ) ),
		];
	}
}

$p = array_map(
	static function( $slug ) {

		return "{$slug}/{$slug}.php";
	},
	wp_list_pluck( $addons_data, 'slug' )
);

/**
 * Invalid function.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function invalid_function() {
	return true;
}

/**
 * Invalid function.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function invalid_function_2() {

	$var = true;
	return $var;
}

/**
 * Invalid function.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function invalid_function_3() {

	if ( true ) {

		return true;
	}

	if ( true ) {
		$krya = true;
		return true;
	}

	while ( true ) {
		echo 'null';
		return true;
	}

	while ( true ) {

		return true;
	}
	return true;

	while ( $b ) {
		// Comment line.
		// Comment line 2.

		/**
		 * Some more comments.
		 */
		return true;
	}
}

