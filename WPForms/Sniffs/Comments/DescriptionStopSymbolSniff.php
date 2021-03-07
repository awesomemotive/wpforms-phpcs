<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use WPForms\Traits\Description;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Traits\GetEntityName;

/**
 * Class DeprecatedTagSniff.
 *
 * @since 1.0.0
 */
class DescriptionStopSymbolSniff extends BaseSniff implements Sniff {

	use GetEntityName;
	use Description;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_FUNCTION,
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
			T_CONST,
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

		$tokens        = $phpcsFile->getTokens();
		$comment_start = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $stackPtr );

		if ( ! $comment_start ) {
			return;
		}

		$first_description_line = $this->findFirstDescriptionLine( $phpcsFile, $comment_start );
		$entity                 = $this->getEntityName( $phpcsFile, $stackPtr, $tokens );

		if ( $tokens[ $comment_start ]['line'] - $tokens[ $first_description_line ]['line'] === 0 ) {
			$phpcsFile->addError(
				sprintf(
					'Move comment description for %s to the next line.',
					$entity
				),
				$comment_start,
				'MissDescription'
			);

			return;
		}

		$last = $this->findLastDescriptionLIne( $phpcsFile, $first_description_line, $tokens );

		if ( ! $this->hasStopSymbol( $last, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Add a stop symbol for %s.',
					$entity
				),
				$last,
				'AddStopSymbol'
			);

			return;
		}
	}
}
