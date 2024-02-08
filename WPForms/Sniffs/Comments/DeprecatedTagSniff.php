<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Traits\Version;
use WPForms\Sniffs\BaseSniff;
use WPForms\Traits\CommentTag;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Traits\GetEntityName;

/**
 * Class DeprecatedTagSniff.
 *
 * @since 1.0.0
 */
class DeprecatedTagSniff extends BaseSniff implements Sniff {

	use GetEntityName;
	use CommentTag;
	use Version;

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

		$tokens       = $phpcsFile->getTokens();
		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $stackPtr );
		$deprecated   = $this->findTag( '@deprecated', $commentStart, $tokens );

		if ( empty( $deprecated ) ) {
			return;
		}

		if ( ! $this->hasVersion( $deprecated, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Missing version for @deprecated tag in a comment for the %s hook.',
					$tokens[ $stackPtr ]['content']
				),
				$deprecated['tag'],
				'MissDeprecatedVersion'
			);

			return;
		}

		if ( ! $this->isValidVersion( $deprecated, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Invalid version for @deprecated tag in a comment for %s.',
					$tokens[ $stackPtr ]['content']
				),
				$deprecated['tag'],
				'InvalidDeprecatedVersion'
			);

			return;
		}

		if (
			! $this->isLastTag( $phpcsFile, $deprecated ) &&
			! $this->hasEmptyLineAfterInComment( $phpcsFile, $deprecated )
		) {
			$phpcsFile->addError(
				sprintf(
					'Add empty line after @deprecated tag for %s.',
					$this->getEntityFullName( $phpcsFile, $stackPtr, $tokens )
				),
				$deprecated['tag'],
				'MissingEmptyLineAfterDeprecated'
			);
		}
	}
}
