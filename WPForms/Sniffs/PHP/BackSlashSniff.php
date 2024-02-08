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
			T_DOC_COMMENT_STRING,
			T_STRING,
		];
	}

	/**
	 * Process this test when one of its tokens is encountered.
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

		if ( $tokens[ $stackPtr ]['code'] === T_DOC_COMMENT_STRING ) {
			$this->processComments( $phpcsFile, $stackPtr );

			return;
		}

		$this->processCode( $phpcsFile, $stackPtr );
	}

	/**
	 * Process code.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function processCode( $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( ! in_array( $tokens[ $stackPtr + 1 ]['code'], [ T_OPEN_PARENTHESIS, T_DOUBLE_COLON ], true ) ) {
			return;
		}

		if ( $tokens[ $stackPtr - 1 ]['code'] !== T_NS_SEPARATOR ) {
			return;
		}

		$startPtr = $phpcsFile->findPrevious( [ T_STRING, T_NS_SEPARATOR ], $stackPtr - 1, null, true );

		if ( $startPtr === false ) {
			return;
		}

		++$startPtr;

		if ( $tokens[ $startPtr ]['code'] !== T_NS_SEPARATOR ) {
			return;
		}

		$name  = $phpcsFile->getTokensAsString( $startPtr, $stackPtr - $startPtr + 1 );
		$count = substr_count( $name, '\\' );

		if ( $count === 1 ) {
			$this->removeBackslashMessage( $phpcsFile, $stackPtr );

			return;
		}

		$this->useShortSyntaxMessage( $phpcsFile, $stackPtr );
	}

	/**
	 * Process comments.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function processComments( $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( $tokens[ $stackPtr - 2 ]['code'] !== T_DOC_COMMENT_TAG ) {
			return;
		}

		if ( preg_match( '~^[\\\]\w+[\\\]~', $tokens[ $stackPtr ]['content'] ) ) {
			$this->useShortSyntaxMessage( $phpcsFile, $stackPtr );

			return;
		}

		if ( preg_match( '~^[\\\]\w+~', $tokens[ $stackPtr ]['content'] ) ) {
			$this->removeBackslashMessage( $phpcsFile, $stackPtr );
		}
	}

	/**
	 * Remove the backslash message.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function removeBackslashMessage( $phpcsFile, $stackPtr ) {

		$phpcsFile->addError(
			'Remove unnecessary backslash',
			$stackPtr,
			'RemoveBackslash'
		);
	}

	/**
	 * Use short syntax instead of full function/object/class name.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function useShortSyntaxMessage( $phpcsFile, $stackPtr ) {

		$phpcsFile->addError(
			'We prefer imports with `use` statement instead of fully qualified names.',
			$stackPtr,
			'UseShortSyntax'
		);
	}
}
