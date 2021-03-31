<?php

namespace WPForms\Sniffs\PHP;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class BackSlashSniff.
 *
 * @since 1.0.0
 */
class BackSlashSniff extends BaseSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_STRING,
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

		if ( ! in_array( $tokens[ $stackPtr + 1 ]['code'], [ T_OPEN_PARENTHESIS, T_DOUBLE_COLON ], true ) ) {
			return;
		}

		if ( $tokens[ $stackPtr - 1 ]['code'] !== T_NS_SEPARATOR ) {
			return;
		}

		if ( $tokens[ $stackPtr - 2 ]['code'] !== T_STRING ) {
			$phpcsFile->addError(
				'Remove unnecessary backslash',
				$stackPtr,
				'RemoveBackslash'
			);

			return;
		}

		$phpcsFile->addError(
			'We prefer a short syntax, so the `use` statement instead',
			$stackPtr,
			'UseShortSyntax'
		);
	}
}
