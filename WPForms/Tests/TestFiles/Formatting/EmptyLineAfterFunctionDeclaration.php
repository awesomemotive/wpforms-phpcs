<?php

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound

/**
 * Valid function.
 *
 * @since 1.0.0
 */
function valid_function() {
}

/**
 * Valid function.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function valid_function_2() {

	return true;
}

/**
 * Valid function.
 *
 * @since 1.0.0
 */
function valid_function_3() {
	?>

	<?php
}

/**
 * Class A.
 *
 * @since 1.0.0
 */
class A {

	/**
	 * Valid method.
	 *
	 * @since 1.0.0
	 */
	public function valid_method() {
	}

	/**
	 * Valid method.
	 *
	 * @since {VERSION}
	 *
	 * @return bool
	 */
	public function valid_method_2() {

		return true;
	}
}

/**
 * Invalid function.
 *
 * @since {VERSION}
 *
 * @return bool
 */
function invalid_function() {
	$var = true;

	return $var;
}

/**
 * Invalid function.
 *
 * @since {VERSION}
 */
function invalid_function_2() {
	echo 'krya'; // phpcs:ignore
}

/**
 * Class B.
 *
 * @since {VERSION}
 */
class B {

	/**
	 * Valid method.
	 *
	 * @since {VERSION}
	 */
	public function in_valid_method() {
		$var = true;

		return $var;
	}

	/**
	 * Valid method.
	 *
	 * @since {VERSION}
	 */
	public function valid_method_2() {
		echo 'krya';
	}
}

// phpcs:enable Generic.Files.OneObjectStructurePerFile.MultipleFound
