<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class PHPDocHooksSniff.
 *
 * @since 1.0.0
 */
class PHPDocHooksSniff extends BaseSniff implements Sniff {

	/**
	 * List of hook functions.
	 *
	 * @since 1.0.0
	 */
	const HOOK_FUNCTIONS = [
		'do_action',
		'apply_filters',
		'do_action_ref_array',
		'apply_filters_ref_array',
		'do_action_deprecated',
		'apply_filters_deprecated',
	];

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

		if ( $tokens[ $stackPtr + 1 ]['code'] !== T_OPEN_PARENTHESIS || ! in_array( $tokens[ $stackPtr ]['content'], self::HOOK_FUNCTIONS, true ) ) {
			return;
		}

		$hookNamePtr = $phpcsFile->findNext( T_CONSTANT_ENCAPSED_STRING, $stackPtr + 1 );
		$hookName    = trim( $tokens[ $hookNamePtr ]['content'], '"\'' );
		$commentEnd  = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr - 1 );

		if ( ! $commentEnd || $tokens[ $commentEnd ]['line'] !== $tokens[ $stackPtr ]['line'] - 1 ) {
			$phpcsFile->addError(
				sprintf(
					'Missing PHPDoc for the %s hook',
					$hookName
				),
				$stackPtr,
				'RequiredHookDocumentation'
			);
		}
	}
}
