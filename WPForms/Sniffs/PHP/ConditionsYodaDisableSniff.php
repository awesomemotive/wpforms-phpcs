<?php

namespace WPForms\Sniffs\PHP;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class ConditionsYodaDisableSniff.
 *
 * @since 1.0.0
 */
class ConditionsYodaDisableSniff extends BaseSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_IS_EQUAL,
			T_IS_NOT_EQUAL,
			T_IS_IDENTICAL,
			T_IS_NOT_IDENTICAL,
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

		$tokens         = $phpcsFile->getTokens();
		$before_compare = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true );
		$after_compare  = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );

		if ( in_array( $tokens[ $before_compare ]['code'], [ T_VARIABLE, T_CLOSE_SQUARE_BRACKET ], true ) ) {
			return;
		}

		// Skip if after not variable.
		if ( $tokens[ $after_compare ]['code'] !== T_VARIABLE ) {
			return;
		}

		// Skip if before method.
		if ( $tokens[ $before_compare ]['code'] === T_CLOSE_PARENTHESIS ) {
			$open_parenthesis = $phpcsFile->findPrevious( T_OPEN_PARENTHESIS, $before_compare - 1 );
			$name             = $phpcsFile->findPrevious( Tokens::$emptyTokens, $open_parenthesis - 1, null, true );

			if ( $tokens[ $name ]['code'] === T_STRING ) {
				return;
			}
		}

		// Allow properties.
		if ( $tokens[ $before_compare ]['code'] === T_STRING && $tokens[ $before_compare - 1 ]['code'] === T_OBJECT_OPERATOR ) {
			return;
		}

		$phpcsFile->addError( 'Remove Yoda Condition this annoying us.', $stackPtr, 'YodaRemove' );
	}
}
