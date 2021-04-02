<?php

namespace WPForms\Traits;

use PHP_CodeSniffer\Files\File;

/**
 * Trait Description.
 *
 * @since 1.0.0
 */
trait Description {

	/**
	 * Find first line in description.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile    The PHP_CodeSniffer file where the token was found.
	 * @param int  $commentStart Position of comment start.
	 *
	 * @return mixed
	 */
	protected function findFirstDescriptionLine( $phpcsFile, $commentStart ) {

		return $phpcsFile->findNext( [ T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR ], $commentStart + 1, null, true );
	}

	/**
	 * Find last line in description.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile            The PHP_CodeSniffer file where the token was found.
	 * @param int   $firstDescriptionLine Position of first description line.
	 * @param array $tokens               Token stack for this file.
	 *
	 * @return int
	 */
	protected function findLastDescriptionLIne( $phpcsFile, $firstDescriptionLine, $tokens ) {

		$lastDescriptionLine = $firstDescriptionLine;

		do {
			$last = $phpcsFile->findNext( [ T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR ], $lastDescriptionLine + 1, null, true );

			if (
				$tokens[ $last ]['line'] - $tokens[ $lastDescriptionLine ]['line'] !== 1 ||
				$tokens[ $last ]['code'] === T_DOC_COMMENT_TAG ||
				$tokens[ $last ]['code'] === T_DOC_COMMENT_CLOSE_TAG
			) {
				return $lastDescriptionLine;
			}
			$lastDescriptionLine = $last;
		} while ( $last );

		return $lastDescriptionLine;
	}

	/**
	 * Line has a stop symbol.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $last   Position of last line.
	 * @param array $tokens Token stack for this file.
	 *
	 * @return bool
	 */
	protected function hasStopSymbol( $last, $tokens ) {

		$lastSymbol = $tokens[ $last ]['content'][ strlen( $tokens[ $last ]['content'] ) - 1 ];

		return in_array( $lastSymbol, [ '.', '!', '?' ], true );
	}
}
