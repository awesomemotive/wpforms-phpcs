<?php

namespace WPForms\Sniffs\Formatting;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class EmptyLineAfterFunctionDeclarationSniff.
 *
 * @since 1.0.0
 */
class EmptyLineAfterFunctionDeclarationSniff extends BaseSniff implements Sniff {

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

		$tokens       = $phpcsFile->getTokens();
		$open_bracket = $phpcsFile->findNext( T_OPEN_CURLY_BRACKET, $stackPtr + 1 );
		$first_line   = $phpcsFile->findNext( Tokens::$emptyTokens, $open_bracket + 1, null, true );

		if ( T_CLOSE_CURLY_BRACKET === $tokens[ $first_line ]['code'] ) {
			return;
		}

		if ( T_CLOSE_TAG === $tokens [ $first_line ]['code'] ) {
			$first_line = $phpcsFile->findNext( Tokens::$emptyTokens, $first_line + 1, null, true );
		}

		if ( $tokens[ $first_line ]['line'] - $tokens[ $open_bracket ]['line'] > 1 ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'Add empty line after %s() function declaration.',
				$phpcsFile->getDeclarationName( $stackPtr )
			),
			$stackPtr,
			'AddEmptyLineAfterFunctionDeclaration'
		);
	}
}
