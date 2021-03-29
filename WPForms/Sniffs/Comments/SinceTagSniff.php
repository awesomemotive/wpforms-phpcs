<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Traits\Version;
use WPForms\Sniffs\BaseSniff;
use WPForms\Traits\CommentTag;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Traits\GetEntityName;

/**
 * Class SinceTagSniff.
 *
 * @since 1.0.0
 */
class SinceTagSniff extends BaseSniff implements Sniff {

	use Version;
	use GetEntityName;
	use CommentTag;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_FUNCTION,
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
			T_CONST,
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

		$tokens        = $phpcsFile->getTokens();
		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $stackPtr );

		if ( empty( $commentStart ) ) {
			return;
		}

		$since  = $this->findTag( '@since', $commentStart, $tokens );
		$entity = $this->getEntityName( $phpcsFile, $stackPtr, $tokens );

		/*
		 * @since is required.
		 */
		if ( empty( $since ) ) {
			$phpcsFile->addError(
				sprintf(
					'@since tag missing for %s.',
					$entity
				),
				$stackPtr,
				'MissingSince'
			);

			return;
		}

		/*
		 * @since should have a version number, basically an ordinary comment.
		 */
		if ( ! $this->hasVersion( $since, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Version missing for @since tag in a comment for %s.',
					$entity
				),
				$since['tag'],
				'MissingSinceVersion'
			);

			return;
		}

		if ( ! $this->isValidVersion( $since, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Invalid version for @since tag in a comment for %s.',
					$entity
				),
				$since['tag'],
				'InvalidSinceVersion'
			);

			return;
		}

		$this->lineBetweenTags( $phpcsFile, $stackPtr, $since );
	}

	/**
	 * Processes and detect empty line between tags.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int   $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 * @param array $since     Since tag token.
	 *
	 * @return void
	 */
	private function lineBetweenTags( $phpcsFile, $stackPtr, $since ) {

		$tokens          = $phpcsFile->getTokens();
		$entity          = $this->getEntityName( $phpcsFile, $stackPtr, $tokens );
		$next_annotation = $phpcsFile->findNext( T_DOC_COMMENT_TAG, $since['tag'] + 1 );
		$commentEnd     = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr );
		$next_annotation = $next_annotation && $tokens[ $commentEnd ]['line'] > $tokens[ $next_annotation ]['line'] ? $next_annotation : false;

		if ( $next_annotation && $tokens[ $next_annotation ]['content'] === '@deprecated' || $tokens[ $next_annotation ]['content'] === '@since' ) {
			if ( $this->hasEmptyLineAfterInComment( $since, $tokens ) ) {
				$phpcsFile->addError(
					sprintf(
						'Remove empty line between @since and @deprecated for %s.',
						$entity
					),
					$since['tag'],
					'EmptyLineBetweenSinceAndDeprecated'
				);
			}

			return;
		}

		if ( ! $this->isLastTag( $since, $tokens ) && ! $this->hasEmptyLineAfterInComment( $since, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Add empty line after @since tag for %s.',
					$entity
				),
				$since['tag'],
				'MissingEmptyLineAfterSince'
			);
		}
	}
}
