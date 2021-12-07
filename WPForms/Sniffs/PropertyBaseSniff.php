<?php

namespace WPForms\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;

/**
 * Class BaseSniff.
 *
 * @since 1.0.0
 */
abstract class PropertyBaseSniff extends AbstractVariableSniff {

	/**
	 * Called to process normal member vars.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where this token was found.
	 * @param int  $stackPtr  The position where the token was found.
	 *
	 * @return void Optionally returns a stack pointer. The sniff will not be
	 *              called again on the current file until the returned stack
	 *              pointer is reached. Return ($phpcsFile->numTokens + 1) to skip
	 *              the rest of the file.
	 */
	protected function processVariable( File $phpcsFile, $stackPtr ) {
	}

	/**
	 * Called to process variables found in double-quoted strings or heredocs.
	 *
	 * Note that there may be more than one variable in the string, which will
	 * result only in one call for the string or one call per line for heredocs.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where this token was found.
	 * @param int  $stackPtr  The position where the double-quoted string was found.
	 *
	 * @return void Optionally returns a stack pointer. The sniff will not be
	 *              called again on the current file until the returned stack
	 *              pointer is reached. Return ($phpcsFile->numTokens + 1) to skip
	 *              the rest of the file.
	 */
	protected function processVariableInString( File $phpcsFile, $stackPtr ) {
	}
}
