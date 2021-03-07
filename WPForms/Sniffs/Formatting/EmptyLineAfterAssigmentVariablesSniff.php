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

		$tokens                   = $phpcsFile->getTokens();
		$first_open_curly_bracket = $phpcsFile->findNext( [ T_OPEN_CURLY_BRACKET ], $stackPtr + 1 );

		// Skip default values for function/methods arguments.
		if ( $first_open_curly_bracket && $tokens[ $first_open_curly_bracket ]['line'] === $tokens[ $stackPtr ]['line'] ) {
			return;
		}

		// Detect the last ";" for detect end of this operation for multiline expressions.
		$semicolon            = $phpcsFile->findNext( [ T_SEMICOLON ], $stackPtr + 1 );
		$next_after_semicolon = $phpcsFile->findNext( Tokens::$emptyTokens, $semicolon + 1, null, true );

		// Skip conditions and loops.
		if ( $next_after_semicolon && $tokens[ $semicolon ]['line'] === $tokens[ $next_after_semicolon ]['line'] ) {
			return;
		}

		$next_line        = $tokens[ $semicolon ]['line'] + 1;
		$next_line_tokens = [];

		for ( $i = $semicolon; $i < count( $tokens ); $i ++ ) {
			if ( $tokens[ $i ]['line'] > $next_line ) {
				break;
			}

			if ( $tokens[ $i ]['line'] !== $next_line ) {
				continue;
			}

			if ( in_array( $tokens[ $i ]['code'], Tokens::$parenthesisOpeners, true ) ) {
				$next_line_tokens[] = $tokens[ $i ];

				break;
			}

			// Skip if it next line is also assignment statement.
			if ( in_array( $tokens[ $i ]['code'], Tokens::$assignmentTokens, true ) ) {
				return;
			}

			// Skip spaces and closed curly brackets.
			if ( in_array( $tokens[ $i ]['code'], [ T_WHITESPACE ], true ) ) {
				continue;
			}

			$next_line_tokens[] = $tokens[ $i ];
		}

		// If next line is empty line.
		if ( empty( $next_line_tokens ) ) {
			return;
		}

		// Skip if the first element is a closed curly bracket.
		if ( $next_line_tokens[0]['code'] === T_CLOSE_CURLY_BRACKET ) {
			return;
		}

		// Allow phpcs comments.
		if ( in_array( $next_line_tokens[0]['code'], Tokens::$phpcsCommentTokens, true ) ) {
			return;
		}

		// Allow closed PHP tag.
		if ( T_CLOSE_TAG === $next_line_tokens[0]['code'] ) {
			return;
		}

		$phpcsFile->addError(
			'Add empty line after assigment statement.',
			$stackPtr,
			'AddEmptyLine'
		);
	}
}
