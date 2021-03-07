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
	 * @param File $phpcsFile     The PHP_CodeSniffer file where the token was found.
	 * @param int  $comment_start Position of comment start.
	 *
	 * @return mixed
	 */
	protected function findFirstDescriptionLine( $phpcsFile, $comment_start ) {

		return $phpcsFile->findNext( [ T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR ], $comment_start + 1, null, true );
	}

	/**
	 * Find last line in description.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile              The PHP_CodeSniffer file where the token was found.
	 * @param int   $first_description_line Position of first description line.
	 * @param array $tokens                 Token stack for this file.
	 *
	 * @return int
	 */
	protected function findLastDescriptionLIne( $phpcsFile, $first_description_line, $tokens ) {

		$last_description_line = $first_description_line;
		do {
			$last = $phpcsFile->findNext( [ T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR ], $last_description_line + 1, null, true );
			if (
				1 !== $tokens[ $last ]['line'] - $tokens[ $last_description_line ]['line'] ||
				T_DOC_COMMENT_TAG === $tokens[ $last ]['code'] ||
				T_DOC_COMMENT_CLOSE_TAG === $tokens[ $last ]['code']
			) {
				return $last_description_line;
			}
			$last_description_line = $last;
		} while ( $last );

		return $last_description_line;
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

		$last_symbol = $tokens[ $last ]['content'][ strlen( $tokens[ $last ]['content'] ) - 1 ];

		return in_array( $last_symbol, [ '.', '!', '?' ], true );
	}
}
