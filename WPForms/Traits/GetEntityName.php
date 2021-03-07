<?php

namespace WPForms\Traits;

use PHP_CodeSniffer\Files\File;

/**
 * Trait GetEntityName.
 *
 * @since 1.0.0
 */
trait GetEntityName {

	/**
	 * Get entity name.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int   $stackPtr  Current position.
	 * @param array $tokens    Token stack for this file.
	 *
	 * @return string
	 */
	protected function getEntityName( $phpcsFile, $stackPtr, $tokens ) {

		$suffix = $this->getSuffix( $tokens[ $stackPtr ]['code'] );
		if ( $tokens[ $stackPtr ]['code'] === T_CONST ) {
			return $tokens[ $stackPtr + 2 ]['content'] . $suffix;
		}

		return $phpcsFile->getDeclarationName( $stackPtr ) . $suffix;
	}

	/**
	 * Get element suffix.
	 *
	 * @since 1.0.0
	 *
	 * @param int $code Code for current element.
	 *
	 * @return string
	 */
	private function getSuffix( $code ) {

		if ( $code === T_FUNCTION ) {
			return '() function';
		}

		if ( $code === T_CLASS ) {
			return ' class';
		}

		if ( $code === T_INTERFACE ) {
			return ' interface';
		}

		if ( $code === T_TRAIT ) {
			return ' trait';
		}

		if ( $code === T_CONST ) {
			return ' constant';
		}

		return '';
	}
}
