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

		$tokens = $phpcsFile->getTokens();

		if ( $tokens[ $stackPtr ]['level'] !== 0 ) {
			return;
		}

		$entity = $phpcsFile->findPrevious( T_STRING, $phpcsFile->findNext( T_SEMICOLON, $stackPtr ) );

		if ( $this->hasEntity( $phpcsFile, $entity ) ) {
			return;
		}

		if ( $this->hasEntityInDoc( $phpcsFile, $entity ) ) {
			return;
		}

		$phpcsFile->addError(
			'Remove unneeded `use` statement',
			$stackPtr,
			'UnusedUseStatement'
		);
	}

	/**
	 * Find function/objects/class with namespace in the code.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $entity    Function/objects/class position that have namespace.
	 *
	 * @return bool
	 */
	private function hasEntity( $phpcsFile, $entity ) {

		$tokens  = $phpcsFile->getTokens();
		$element = $phpcsFile->findNext( T_STRING, $entity + 1, null, false, $tokens[ $entity ]['content'] );

		if ( ! $element ) {
			return false;
		}

		if ( $tokens[ $element ]['level'] === 0 && $tokens[ $element + 1 ]['code'] === T_SEMICOLON ) {
			return $this->hasEntity( $phpcsFile, $element );
		}

		return true;
	}

	/**
	 * Find function/objects/class with namespace in the PHPDoc.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $entity    Function/objects/class position that have namespace.
	 *
	 * @return bool
	 */
	private function hasEntityInDoc( $phpcsFile, $entity ) {

		$tokens     = $phpcsFile->getTokens();
		$entityName = $tokens[ $entity ]['content'];
		$element    = $phpcsFile->findNext( T_DOC_COMMENT_STRING, $entity + 1, null, false, $entityName );

		if ( ! empty( $element ) ) {
			return true;
		}

		return $this->findInParamsDescription( $phpcsFile, $entityName, $entity );
	}

	/**
	 * Find function/objects/class with namespace in the PHPDoc.
	 *
	 * @since 1.0.0
	 *
	 * @param File   $phpcsFile  The PHP_CodeSniffer file where the token was found.
	 * @param string $entityName Function/objects/class name.
	 * @param int    $element    Last search position.
	 *
	 * @return bool
	 */
	private function findInParamsDescription( $phpcsFile, $entityName, $element ) {

		$tokens      = $phpcsFile->getTokens();
		$nextElement = $phpcsFile->findNext( T_DOC_COMMENT_TAG, $element + 1 );

		if ( empty( $nextElement ) ) {
			return false;
		}

		if ( $this->findInThrows( $phpcsFile, $entityName, $nextElement ) ) {
			return true;
		}

		if ( $tokens[ $nextElement ]['content'] !== '@param' ) {
			return $this->findInParamsDescription( $phpcsFile, $entityName, $nextElement );
		}

		$paramDescription = $nextElement + 2;

		if ( $tokens[ $paramDescription ]['code'] !== T_DOC_COMMENT_STRING ) {
			return $this->findInParamsDescription( $phpcsFile, $entityName, $nextElement );
		}

		if ( strpos( $tokens[ $paramDescription ]['content'], $entityName ) === 0 ) {
			return true;
		}

		return $this->findInParamsDescription( $phpcsFile, $entityName, $nextElement );
	}

	/**
	 * Find function/objects/class in the PHPDoc @throws.
	 *
	 * @since {VERSION}
	 *
	 * @param File   $phpcsFile  The PHP_CodeSniffer file where the token was found.
	 * @param string $entityName Function/objects/class name.
	 * @param int    $stackPtr   Current search position.
	 *
	 * @return bool
	 */
	private function findInThrows( $phpcsFile, $entityName, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( $tokens[ $stackPtr ]['content'] === '@throws' ) {
			$closePtr   = $phpcsFile->findNext( T_DOC_COMMENT_CLOSE_TAG, $stackPtr + 1 );
			$commentPtr = $phpcsFile->findNext( T_DOC_COMMENT_STRING, $stackPtr + 1, $closePtr );

			if ( $commentPtr && $entityName === explode( ' ', $tokens[ $commentPtr ]['content'] )[0] ) {
				return true;
			}
		}

		return false;
	}
}
