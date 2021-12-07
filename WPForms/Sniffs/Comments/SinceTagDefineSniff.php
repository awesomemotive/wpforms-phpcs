<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Traits\Version;
use WPForms\Sniffs\BaseSniff;
use WPForms\Traits\CommentTag;
use PHP_CodeSniffer\Files\File;
use WPForms\Traits\Description;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class SinceTagDefineSniff.
 *
 * @since 1.0.0
 */
class SinceTagDefineSniff extends BaseSniff implements Sniff {

	use CommentTag;
	use Version;
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

		if ( $tokens[ $stackPtr ]['content'] !== 'define' ) {
			return;
		}

		$commentEnd = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr );

		if ( ! $commentEnd || $tokens[ $commentEnd ]['line'] !== $tokens[ $stackPtr ]['line'] - 1 ) {
			return;
		}

		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $commentEnd );
		$since        = $this->findTag( '@since', $commentStart, $tokens );

		if ( empty( $since ) ) {
			$phpcsFile->addError(
				'Miss the @since tag for the define function',
				$stackPtr,
				'MissSinceTag'
			);

			return;
		}

		$this->processVersion( $phpcsFile, $since );

		if ( $this->hasEmptyLineAfterInComment( $phpcsFile, $since ) ) {
			$phpcsFile->addError(
				'Remove empty line after @since tag',
				$since['tag'],
				'EmptyLineAfterSince'
			);
		}
	}

	/**
	 * Process version.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param array $since     Since tag token.
	 */
	private function processVersion( $phpcsFile, $since ) {

		$tokens = $phpcsFile->getTokens();

		if ( ! $this->hasVersion( $since, $tokens ) ) {
			$phpcsFile->addError(
				'Version missing for @since tag in a comment for the define function',
				$since['tag'],
				'MissingSinceVersion'
			);

			return;
		}

		if ( ! $this->isValidVersion( $since, $tokens ) ) {
			$phpcsFile->addError(
				'Invalid version for @since tag in a comment for the define hook.',
				$since['tag'],
				'InvalidSinceVersion'
			);
		}
	}
}
