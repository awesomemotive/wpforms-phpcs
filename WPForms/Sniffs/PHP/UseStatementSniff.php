<?php

namespace WPForms\Sniffs\PHP;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class UseStatementSniff.
 *
 * @since 1.0.0
 */
class UseStatementSniff extends BaseSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_USE,
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

		$entity = $phpcsFile->findPrevious( T_STRING, $phpcsFile->findNext( T_SEMICOLON, $stackPtr ) );

		if ( $this->findElement( $phpcsFile, $entity ) ) {
			return;
		}

		$phpcsFile->addError(
			'',
			$stackPtr,
			'UnusedUseStatement'
		);
	}

	/**
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param      $entity
	 */
	private function findElement( $phpcsFile, $entity ) {

		$tokens  = $phpcsFile->getTokens();
		$element = $phpcsFile->findNext( T_STRING, $entity + 1, null, false, $tokens[ $entity ]['content'] );

		if ( ! $element ) {
			return false;
		}

		if ( ! in_array( $tokens[ $element + 1 ]['code'], [ T_OPEN_PARENTHESIS, T_DOUBLE_COLON ], true ) ) {
			return $this->findElement( $phpcsFile, $element );
		}

		return true;
	}
}
