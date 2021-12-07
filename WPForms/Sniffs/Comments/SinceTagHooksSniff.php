<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Traits\Version;
use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Traits\DuplicateHook;

/**
 * Class SinceTagHooksSniff.
 *
 * @since 1.0.0
 */
class SinceTagHooksSniff extends BaseSniff implements Sniff {

	use Version;
	use DuplicateHook;

	/**
	 * List of hook functions.
	 *
	 * @since 1.0.0
	 */
	const HOOK_FUNCTIONS = [
		'do_action',
		'apply_filters',
		'do_action_ref_array',
		'apply_filters_ref_array',
		'do_action_deprecated',
		'apply_filters_deprecated',
	];

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

		if ( $tokens[ $stackPtr + 1 ]['code'] !== T_OPEN_PARENTHESIS || ! in_array( $tokens[ $stackPtr ]['content'], self::HOOK_FUNCTIONS, true ) ) {
			return;
		}

		$commentEnd   = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr - 1 );
		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $commentEnd );

		if ( $this->isDuplicateHook( $commentStart, $tokens ) ) {
			return;
		}

		$hookNamePtr = $phpcsFile->findNext( T_CONSTANT_ENCAPSED_STRING, $stackPtr + 1 );
		$hookName    = trim( $tokens[ $hookNamePtr ]['content'], '"\'' );
		$since       = $this->findTag( '@since', $commentStart, $tokens );

		if ( empty( $since ) ) {
			$phpcsFile->addError(
				sprintf(
					'Miss the @since tag for the %s hook',
					$hookName
				),
				$stackPtr,
				'MissSinceTag'
			);

			return;
		}

		$this->processVersion( $phpcsFile, $since, $hookName );
		$this->lineBetweenTags( $phpcsFile, $stackPtr, $since );
	}

	/**
	 * Process version.
	 *
	 * @since 1.0.0
	 *
	 * @param File   $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param array  $since     Since tag token.
	 * @param string $hookName  Hook name.
	 */
	private function processVersion( $phpcsFile, $since, $hookName ) {

		$tokens = $phpcsFile->getTokens();

		if ( ! $this->hasVersion( $since, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Version missing for @since tag in a comment for the %s hook.',
					$hookName
				),
				$since['tag'],
				'MissingSinceVersion'
			);

			return;
		}

		if ( ! $this->isValidVersion( $since, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Invalid version for @since tag in a comment for the %s hook.',
					$hookName
				),
				$since['tag'],
				'InvalidSinceVersion'
			);
		}
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

		$tokens         = $phpcsFile->getTokens();
		$hookNamePtr    = $phpcsFile->findNext( T_CONSTANT_ENCAPSED_STRING, $stackPtr + 1 );
		$hookName       = trim( $tokens[ $hookNamePtr ]['content'], '"\'' );
		$nextAnnotation = $phpcsFile->findNext( T_DOC_COMMENT_TAG, $since['tag'] + 1 );
		$commentEnd     = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr );
		$nextAnnotation = $nextAnnotation && $tokens[ $commentEnd ]['line'] > $tokens[ $nextAnnotation ]['line'] ? $nextAnnotation : false;

		if ( ( $nextAnnotation && $tokens[ $nextAnnotation ]['content'] === '@deprecated' ) || $tokens[ $nextAnnotation ]['content'] === '@since' ) {
			if ( $this->hasEmptyLineAfterInComment( $phpcsFile, $since ) ) {
				$phpcsFile->addError(
					sprintf(
						'Remove empty line between @since and @deprecated for the %s hook.',
						$hookName
					),
					$since['tag'],
					'EmptyLineBetweenSinceAndDeprecated'
				);
			}

			return;
		}

		if (
			! $this->isLastTag( $phpcsFile, $since ) &&
			! $this->hasEmptyLineAfterInComment( $phpcsFile, $since )
		) {
			$phpcsFile->addError(
				sprintf(
					'Add empty line after @since tag for the %s hook.',
					$hookName
				),
				$since['tag'],
				'MissingEmptyLineAfterSince'
			);
		}
	}
}
