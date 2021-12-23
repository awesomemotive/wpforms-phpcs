<?php

namespace WPForms\Tests\TestedFiles\PHP;

class ValidateHooks {

	public function useHooks() {

		$extra = 'extra';

		// Valid.
		apply_filters( 'wpforms_tests_testedfiles_php_validate_hooks', true );
		apply_filters( 'wpforms_tests_testedfiles_php_validate_hooks_extra_detail', true );
		do_action( 'wpforms_tests_testedfiles_php_validate_hooks', true );
		do_action( 'wpforms_tests_testedfiles_php_validate_hooks_extra_detail', true );

		apply_filters( "wpforms_tests_testedfiles_php_validate_hooks_{$extra}_detail", true );
		do_action( "wpforms_tests_testedfiles_php_validate_hooks_{$extra}_detail" );

		// Invalid.
		apply_filters( 'tests_testedfiles_php_validate_hooks', true );
		apply_filters( 'tests_testedfiles_php_validate_hooks_extra_detail', true );
		do_action( 'tests_testedfiles_php_validate_hooks', true );
		do_action( 'tests_testedfiles_php_validate_hooks_extra_detail', true );

		apply_filters( 'invalid_wpforms_tests_testedfiles_php_validate_hooks', true );
		do_action( 'invalid_wpforms_tests_testedfiles_php_validate_hooks', true );
	}
}
