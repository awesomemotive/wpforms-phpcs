<?php

namespace WPForms\Sniffs\Comments;

use PHP_CodeSniffer\Files\File;
use WPForms\Traits\Description;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Traits\GetEntityName;
use WPForms\Sniffs\PropertyBaseSniff;

/**
 * Class DeprecatedTagSniff.
 *
 * @since 1.0.0
 */
class DescriptionStopSymbolPropertySniff extends PropertyBaseSniff implements Sniff {

	use GetEntityName;
	use Description;

	/**
	 * Called to process class member vars.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where this token was found.
	 * @param int  $stackPtr  The position where the token was found.
	 *
	 * @return void|int Optionally returns a stack pointer. The sniff will not be
	 *                  called again on the current file until the returned stack
	 *                  pointer is reached. Return ($phpcsFile->numTokens + 1) to skip
	 *                  the rest of the file.
	 */
	protected function processMemberVar( File $phpcsFile, $stackPtr ) {

		$tokens               = $phpcsFile->getTokens();
		$commentStart         = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $stackPtr );
		$firstDescriptionLine = $this->findFirstDescriptionLine( $phpcsFile, $commentStart );

		if ( $tokens[ $commentStart ]['line'] - $tokens[ $firstDescriptionLine ]['line'] === 0 ) {
			$phpcsFile->addError(
				sprintf(
					'Move comment description for %s to the next line.',
					$tokens[ $stackPtr ]['content']
				),
				$commentStart,
				'MissDescription'
			);

			return;
		}

		$last = $this->findLastDescriptionLIne( $phpcsFile, $firstDescriptionLine, $tokens );

		if ( ! $this->hasStopSymbol( $last, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Add a stop symbol for %s.',
					$tokens[ $stackPtr ]['content']
				),
				$last,
				'AddStopSymbol'
			);

			return;
		}
	}
}
