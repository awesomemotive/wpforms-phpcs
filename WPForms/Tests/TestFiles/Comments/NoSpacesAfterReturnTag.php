<?php

/**
 * Class ReturnTag.
 *
 * @since 1.0.0
 */
class ReturnTag {

	/**
	 * Valid method.
	 *
	 * @since   1.0.0
	 *
	 * @param string $arg1 Arg 1.
	 * @param int    $arg2 Arg 2.
	 * @param array  $arg3 Arg 3.
	 *
	 * @return string
	 */
	public function valid_test( $arg1, $arg2, $arg3 ) {

		return '';
	}

	/**
	 * Invalid method.
	 *
	 * @since 1.0.0
	 *
	 * @param string $arg1 Arg 1.
	 * @param int    $arg2 Arg 2.
	 * @param array  $arg3 Arg 3.
	 *
	 * @return  string
	 */
	public function invalid_test( $arg1, $arg2, $arg3 ) {

		return '';
	}
}
