<?php

namespace WPForms\Sniffs;

/**
 * Class BaseSniff.
 *
 * @since 1.1.0
 */
abstract class BaseSniff {

	/**
	 * Log into the console. Accepts any number of vars.
	 *
	 * @since 1.1.0
	 */
	protected function log() {

		foreach ( func_get_args() as $var ) {
			echo print_r( $var, true ) . PHP_EOL;
		}
	}
}
