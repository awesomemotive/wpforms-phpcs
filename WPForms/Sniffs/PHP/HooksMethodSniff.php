<?php

namespace WPForms\Sniffs\PHP;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class HooksMethodSniff.
 *
 * @since 1.0.0
 */
class HooksMethodSniff extends BaseSniff implements Sniff {

	/**
	 * Functions that allow adding callback for hooks.
	 */
	const ADD_HOOKS_FUNCTIONS = [
		'add_action',
		'add_filter',
		'remove_action',
		'remove_filter',
	];

	/**
	 * Method name.
	 */
	const METHOD_NAME = 'hooks';

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_STRING,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( ! in_array( $tokens[ $stackPtr ]['content'], self::ADD_HOOKS_FUNCTIONS, true ) ) {
			return;
		}

		$function = $phpcsFile->findNext( T_STRING, $phpcsFile->findPrevious( T_FUNCTION, $stackPtr ) );

		if ( self::METHOD_NAME === $tokens[ $function ]['content'] ) {
			return;
		}

		$phpcsFile->addError(
			'Hooks can only be added in the `hooks` method',
			$function,
			'InvalidPlaceForAddingHooks'
		);
	}
}
