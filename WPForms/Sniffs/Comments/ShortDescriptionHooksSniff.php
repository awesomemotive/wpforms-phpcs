<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use WPForms\Traits\Description;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Traits\DuplicateHook;

/**
 * Class ShortDescriptionHooksSniff.
 *
 * @since 1.0.0
 */
class ShortDescriptionHooksSniff extends BaseSniff implements Sniff {

	use Description;
	use DuplicateHook;

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

		$commentEnd = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr - 1 );

		if ( ! $commentEnd || $tokens[ $commentEnd ]['line'] !== $tokens[ $stackPtr ]['line'] - 1 ) {
			return;
		}

		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $commentEnd );

		if ( $this->isDuplicateHook( $commentStart, $tokens ) ) {
			return;
		}

		$shortDescription = $phpcsFile->findNext( [ T_DOC_COMMENT_TAG, T_DOC_COMMENT_STRING ], $commentStart );

		if ( empty( $shortDescription ) || $tokens[ $shortDescription ]['code'] === T_DOC_COMMENT_TAG ) {
			$phpcsFile->addError(
				'Add the short description',
				$commentStart,
				'MissShortDescription'
			);

			return;
		}

		$last = $this->findLastDescriptionLIne( $phpcsFile, $shortDescription, $tokens );

		if ( ! $this->hasStopSymbol( $last, $tokens ) ) {
			$phpcsFile->addError(
				'Add a stop symbol for the short description.',
				$last,
				'AddStopSymbol'
			);

			return;
		}
	}
}
