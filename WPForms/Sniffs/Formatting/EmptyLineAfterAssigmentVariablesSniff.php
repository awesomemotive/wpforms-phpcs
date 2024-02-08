<?php

namespace WPForms\Sniffs\Formatting;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class EmptyLineAfterAssigmentVariablesSniff.
 *
 * @since 1.0.0
 */
class EmptyLineAfterAssigmentVariablesSniff extends BaseSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return array_diff( Tokens::$assignmentTokens, [ T_DOUBLE_ARROW ] );
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

		$tokens                = $phpcsFile->getTokens();
		$firstOpenCurlyBracket = $phpcsFile->findNext( [ T_OPEN_CURLY_BRACKET ], $stackPtr + 1 );

		// Skip default values for function/methods arguments.
		if ( $firstOpenCurlyBracket && $tokens[ $firstOpenCurlyBracket ]['line'] === $tokens[ $stackPtr ]['line'] ) {
			return;
		}

		// Detect the last ";" for detect the end of this operation for multiline expressions.
		$semicolon          = $phpcsFile->findNext( [ T_SEMICOLON ], $stackPtr + 1 );
		$nextAfterSemicolon = $phpcsFile->findNext( Tokens::$emptyTokens, $semicolon + 1, null, true );

		// Skip conditions and loops.
		if ( $nextAfterSemicolon && $tokens[ $semicolon ]['line'] === $tokens[ $nextAfterSemicolon ]['line'] ) {
			return;
		}

		$nextLineTokens = $this->getTokenNextLine( $phpcsFile, $semicolon );

		// If the next line is an empty line.
		if ( empty( $nextLineTokens ) ) {
			return;
		}

		if (
			$this->isBreakInSwitch( $phpcsFile, $nextLineTokens[0] ) ||
			in_array( $nextLineTokens[0]['code'], $this->getAllowedTokensAfterAssigment(), true )
		) {
			return;
		}

		$phpcsFile->addError(
			'Add empty line after assigment statement.',
			$stackPtr,
			'AddEmptyLine'
		);
	}

	/**
	 * Get the list of tokens that allowed in the next line after assigment.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function getAllowedTokensAfterAssigment() {

		return array_merge(
			[
				T_CLOSE_CURLY_BRACKET,
				T_CLOSE_TAG,
			],
			Tokens::$phpcsCommentTokens
		);
	}

	/**
	 * Get tokens in the last line.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $semicolon Semicolon position.
	 *
	 * @return array
	 */
	private function getTokenNextLine( $phpcsFile, $semicolon ) {

		$tokens         = $phpcsFile->getTokens();
		$nextLine       = $tokens[ $semicolon ]['line'] + 1;
		$nextLineTokens = [];
		$count          = count( $tokens );

		for ( $i = $semicolon; $i < $count; $i++ ) {
			if ( $tokens[ $i ]['line'] > $nextLine ) {
				break;
			}

			// Skip current line.
			if ( $tokens[ $i ]['line'] !== $nextLine || in_array( $tokens[ $i ]['code'], Tokens::$emptyTokens, true ) ) {
				continue;
			}

			if ( in_array( $tokens[ $i ]['code'], Tokens::$parenthesisOpeners, true ) ) {
				$nextLineTokens[] = $tokens[ $i ];

				break;
			}

			// Skip if the next line is also an assignment statement.
			if ( in_array( $tokens[ $i ]['code'], Tokens::$assignmentTokens, true ) ) {
				return [];
			}

			$nextLineTokens[] = $tokens[ $i ];
		}

		return $nextLineTokens;
	}
}
