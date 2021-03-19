<?php

namespace WPForms\Sniffs\Formatting;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class EmptyLineBeforeReturnSniff.
 *
 * @since 1.0.0
 */
class EmptyLineBeforeReturnSniff extends BaseSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_RETURN,
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

		$tokens               = $phpcsFile->getTokens();
		$previous             = $phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true );
		$statement            = $phpcsFile->findStartOfStatement( $previous - 1 );
		$important_line_after = array_merge( [ T_FUNCTION, T_STATIC ], Tokens::$scopeModifiers );

		// Don't allow empty line for statements with only return in a body.
		if (
			$tokens[ $previous ]['code'] === T_OPEN_CURLY_BRACKET &&
			! in_array( $tokens[ $statement ]['code'], $important_line_after, true )
		) {
			if ( $tokens[ $stackPtr ]['line'] - $tokens[ $previous ]['line'] > 1 ) {
				$phpcsFile->addError(
					sprintf(
						'Remove empty line before return statement in %d line.',
						$tokens[ $stackPtr ]['line'] - 1
					),
					$stackPtr,
					'RemoveEmptyLineBeforeReturnStatement'
				);
			}

			return;
		}

		if ( $tokens[ $stackPtr ]['line'] - $tokens[ $previous ]['line'] < 2 ) {
			$phpcsFile->addError(
				sprintf(
					'Add empty line before return statement in %d line.',
					$tokens[ $stackPtr ]['line'] - 1
				),
				$stackPtr,
				'AddEmptyLineBeforeReturnStatement'
			);
		}
	}
}
