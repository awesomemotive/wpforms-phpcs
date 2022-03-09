<?php

namespace WPForms\Sniffs\Formatting;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class SwitchSniff.
 *
 * @since 1.0.0
 */
class SwitchSniff extends BaseSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_SWITCH,
			T_CASE,
			T_DEFAULT,
			T_BREAK,
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

		if ( $tokens[ $stackPtr ]['code'] === T_SWITCH ) {
			$this->processSwitch( $phpcsFile, $stackPtr );
		}

		if ( in_array( $tokens[ $stackPtr ]['code'], [ T_CASE, T_DEFAULT ], true ) ) {
			$this->processCase( $phpcsFile, $stackPtr );
		}

		if ( $this->isBreakInSwitch( $phpcsFile, $tokens[ $stackPtr ] ) ) {
			$this->processBreak( $phpcsFile, $stackPtr );
		}
	}

	/**
	 * Process the switch element.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function processSwitch( File $phpcsFile, $stackPtr ) {

		$tokens   = $phpcsFile->getTokens();
		$previous = $phpcsFile->findPrevious( [ T_WHITESPACE, T_COMMENT ], $stackPtr - 1, null, true );

		if ( $previous === false || $tokens[ $previous ]['code'] === T_COMMENT ) {
			return;
		}

		if ( $this->lineDistance( $phpcsFile, $stackPtr, $previous ) === 1 ) {
			$this->addEmptyLineError( $phpcsFile, $stackPtr );
		}

		$beforeClose = $phpcsFile->findPrevious( T_WHITESPACE, $tokens[ $stackPtr ]['scope_closer'] - 1, null, true );

		if ( $this->lineDistance( $phpcsFile, $tokens[ $stackPtr ]['scope_closer'], $beforeClose ) !== 1 ) {
			$this->removeEmptyLineError( $phpcsFile, $tokens[ $stackPtr ]['scope_closer'] );
		}

		$next = $phpcsFile->findNext( T_WHITESPACE, $tokens[ $stackPtr ]['scope_closer'] + 1, null, true );

		if ( $next === false || ( $tokens[ $next ]['code'] === T_CLOSE_CURLY_BRACKET ) ) {
			return;
		}

		if ( $this->lineDistance( $phpcsFile, $next, $tokens[ $stackPtr ]['scope_closer'] ) > 1 ) {
			return;
		}

		$this->addEmptyLineError( $phpcsFile, $next );
	}

	/**
	 * Process the case element.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function processCase( File $phpcsFile, $stackPtr ) {

		$tokens            = $phpcsFile->getTokens();
		$previous          = $phpcsFile->findPrevious( [ T_WHITESPACE, T_COMMENT ], $stackPtr - 1, null, true );
		$previousStatement = $phpcsFile->findFirstOnLine( [ T_SWITCH, T_CASE, T_DEFAULT, T_BREAK ], $previous );

		if ( $previousStatement === false ) {
			$this->addEmptyLineError( $phpcsFile, $stackPtr );

			return;
		}

		if (
			$tokens[ $previousStatement ]['code'] === T_SWITCH &&
			$this->lineDistance( $phpcsFile, $stackPtr, $previousStatement ) !== 1
		) {
			$this->removeEmptyLineError( $phpcsFile, $stackPtr );

			return;
		}

		if (
			$tokens[ $previousStatement ]['code'] === T_BREAK &&
			$this->lineDistance( $phpcsFile, $stackPtr, $previousStatement ) === 1
		) {
			$this->addEmptyLineError( $phpcsFile, $stackPtr );

			return;
		}

		if (
			$this->lineDistance( $phpcsFile, $stackPtr, $previousStatement ) !== 1 &&
			in_array( $tokens[ $previousStatement ]['code'], [ T_CASE, T_DEFAULT ], true )
		) {
			$this->removeEmptyLineError( $phpcsFile, $stackPtr );
		}
	}

	/**
	 * Get distance in lines between tokens, ignoring comment lines.
	 *
	 * @since 1.0.4
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $endPtr    End token.
	 * @param int  $startPtr  Start token.
	 *
	 * @return mixed
	 */
	private function lineDistance( File $phpcsFile, $endPtr, $startPtr ) {

		$tokens = $phpcsFile->getTokens();

		$ptr = $startPtr + 1;

		$commentLines = [];

		while ( $ptr < $endPtr ) {
			if (
				$tokens[ $ptr ]['code'] === T_COMMENT &&
				$phpcsFile->findFirstOnLine( [ T_WHITESPACE, T_COMMENT ], $ptr, true ) === false
			) {
				$commentLines[] = $tokens[ $ptr ]['line'];
			}

			$ptr ++;
		}

		return max(
			0,
			$tokens[ $endPtr ]['line'] - $tokens[ $startPtr ]['line'] - count( array_unique( $commentLines ) )
		);
	}

	/**
	 * Process the break element.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function processBreak( File $phpcsFile, $stackPtr ) {

		$tokens   = $phpcsFile->getTokens();
		$previous = $phpcsFile->findPrevious( T_WHITESPACE, $stackPtr - 1, null, true );

		if ( $tokens[ $previous ]['code'] === T_OPEN_CURLY_BRACKET ) {
			return;
		}

		$previousStatement = $phpcsFile->findFirstOnLine( [ T_CASE, T_DEFAULT ], $previous );

		if ( empty( $previousStatement ) && $tokens[ $stackPtr ]['line'] - $tokens[ $previous ]['line'] !== 1 ) {
			$this->removeEmptyLineError( $phpcsFile, $stackPtr );
		}
	}

	/**
	 * Add error to add an empty line.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function addEmptyLineError( $phpcsFile, $stackPtr ) {

		$phpcsFile->addError(
			'You should add an empty line before',
			$stackPtr,
			'AddEmptyLineBefore'
		);
	}

	/**
	 * Add error to remove an empty line.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 */
	private function removeEmptyLineError( $phpcsFile, $stackPtr ) {

		$phpcsFile->addError(
			'You should remove an empty line before',
			$stackPtr,
			'RemoveEmptyLineBefore'
		);
	}
}
