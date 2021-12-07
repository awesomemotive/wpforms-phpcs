<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use WPForms\Traits\Description;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class TranslatorsSniff.
 *
 * @since 1.0.0
 */
class TranslatorsSniff extends BaseSniff implements Sniff {

	use Description;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_COMMENT,
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

		$tokens  = $phpcsFile->getTokens();
		$content = $tokens[ $stackPtr ]['content'];

		if ( 3 !== strpos( $content, 'translators' ) ) {
			return;
		}

		if ( 3 !== strpos( $content, 'translators:' ) ) {
			$phpcsFile->addError(
				'You should add the colon after translators',
				$stackPtr,
				'MissColon'
			);

			return;
		}

		if ( $this->processShortDescription( $phpcsFile, $stackPtr ) ) {
			return;
		}

		if ( $content[1] !== '*' ) {
			$phpcsFile->addError(
				'Use /* ... */ comments instead',
				$stackPtr,
				'InvalidCommentTag'
			);
		}
	}

	/**
	 * Process a short description.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 *
	 * @return bool
	 */
	private function processShortDescription( $phpcsFile, $stackPtr ) {

		$tokens  = $phpcsFile->getTokens();
		$content = $tokens[ $stackPtr ]['content'];

		preg_match( '/translators:(.*)/', $content, $matches );

		if ( empty( $matches[1] ) ) {
			$phpcsFile->addError(
				'Add a short description',
				$stackPtr,
				'MissShortDescription'
			);

			return true;
		}

		$shortDescription = trim( preg_replace( '/\*\/$/', '', $matches[1] ) );

		if ( empty( $shortDescription ) ) {
			$phpcsFile->addError(
				'Add a short description',
				$stackPtr,
				'MissShortDescription'
			);

			return true;
		}

		if ( ! in_array( $shortDescription[ strlen( $shortDescription ) - 1 ], [ '.', '!', '?' ], true ) ) {
			$phpcsFile->addError(
				'Add a stop symbol',
				$stackPtr,
				'MissStopSymbol'
			);

			return true;
		}

		return false;
	}
}
