<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Traits\Version;
use WPForms\Traits\CommentTag;
use PHP_CodeSniffer\Files\File;
use WPForms\Sniffs\PropertyBaseSniff;

/**
 * Class SinceTagPropertiesSniff.
 *
 * @since 1.0.0
 */
class SinceTagPropertySniff extends PropertyBaseSniff {

	use Version;
	use CommentTag;

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

		$tokens        = $phpcsFile->getTokens();
		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $stackPtr );

		if ( ! $commentStart ) {
			return;
		}

		$since = $this->findTag( '@since', $commentStart, $tokens );

		if ( empty( $since ) ) {
			$phpcsFile->addError(
				sprintf(
					'@since tag missing for %s property.',
					$tokens[ $stackPtr ]['content']
				),
				$stackPtr,
				'MissingPhpDoc'
			);

			return;
		}

		if ( ! $this->hasVersion( $since, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Version missing for @since tag in a comment for %s property.',
					$tokens[ $stackPtr ]['content']
				),
				$since['tag'],
				'MissingSinceVersion'
			);

			return;
		}

		if ( ! $this->isValidVersion( $since, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Invalid version for @since tag in a comment for %s property.',
					$tokens[ $stackPtr ]['content']
				),
				$since['tag'],
				'InvalidSinceVersion'
			);

			return;
		}

		$this->lineBetweenTags( $phpcsFile, $stackPtr, $since );
	}

	/**
	 * Processes and detect empty line between tags.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int   $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 * @param array $since     Since tag token.
	 *
	 * @return void
	 */
	private function lineBetweenTags( $phpcsFile, $stackPtr, $since ) {

		$tokens          = $phpcsFile->getTokens();
		$next_annotation = $phpcsFile->findNext( T_DOC_COMMENT_TAG, $since['tag'] + 1 );
		$commentEnd     = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr );
		$next_annotation = $next_annotation && $tokens[ $commentEnd ]['line'] > $tokens[ $next_annotation ]['line'] ? $next_annotation : false;

		if ( $next_annotation && $tokens[ $next_annotation ]['content'] === '@deprecated' || $tokens[ $next_annotation ]['content'] === '@since' ) {
			if ( $this->hasEmptyLineAfterInComment( $since, $tokens ) ) {
				$phpcsFile->addError(
					sprintf(
						'Remove empty line between @since and @deprecated for %s property.',
						$tokens[ $stackPtr ]['content']
					),
					$since['tag'],
					'EmptyLineBetweenSinceAndDeprecated'
				);
			}

			return;
		}

		if ( ! $this->isLastTag( $since, $tokens ) && ! $this->hasEmptyLineAfterInComment( $since, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Add empty line after @since tag for %s property.',
					$tokens[ $stackPtr ]['content']
				),
				$since['tag'],
				'MissingEmptyLineAfterSince'
			);
		}
	}
}
