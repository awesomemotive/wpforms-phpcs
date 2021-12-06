<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Traits\Version;
use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Traits\GetEntityName;
use WPForms\Traits\DuplicateHook;

/**
 * Class DeprecatedTagHooksSniff.
 *
 * @since 1.0.0
 */
class DeprecatedTagHooksSniff extends BaseSniff implements Sniff {

	use GetEntityName;
	use Version;
	use DuplicateHook;

	/**
	 * List of functions which require deprecated tag.
	 *
	 * @since 1.0.0
	 */
	const REQUIRED_DEPRECATED_FUNCTIONS = [
		'do_action_deprecated',
		'apply_filters_deprecated',
	];

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

		if ( ! in_array( $tokens[ $stackPtr ]['content'], self::HOOK_FUNCTIONS, true ) ) {
			return;
		}

		$commentEnd = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr );

		if ( ! $commentEnd || $tokens[ $commentEnd ]['line'] !== $tokens[ $stackPtr ]['line'] - 1 ) {
			return;
		}

		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $commentEnd );
		$deprecated   = $this->findTag( '@deprecated', $commentStart, $tokens );
		$hookNamePtr  = $phpcsFile->findNext( T_CONSTANT_ENCAPSED_STRING, $stackPtr + 1 );
		$hookName     = trim( $tokens[ $hookNamePtr ]['content'], '"\'' );

		if ( $this->isDuplicateHook( $commentStart, $tokens ) ) {
			return;
		}

		$this->processTagPosition( $phpcsFile, $hookName, $stackPtr, $deprecated );

		if ( empty( $deprecated ) ) {
			return;
		}

		$this->processVersion( $phpcsFile, $hookName, $deprecated );
		$this->processLines( $phpcsFile, $hookName, $deprecated );
	}

	/**
	 * Check deprecated tag position.
	 *
	 * @since 1.0.0
	 *
	 * @param File   $phpcsFile  The PHP_CodeSniffer file where the token was found.
	 * @param string $hookName   Hook name.
	 * @param int    $stackPtr   The position in the PHP_CodeSniffer file's token stack where the token was found.
	 * @param array  $deprecated Deprecated tag data.
	 */
	private function processTagPosition( $phpcsFile, $hookName, $stackPtr, $deprecated ) {

		$tokens = $phpcsFile->getTokens();

		if ( ! empty( $deprecated ) && ! in_array( $tokens[ $stackPtr ]['content'], self::REQUIRED_DEPRECATED_FUNCTIONS, true ) ) {
			$phpcsFile->addError(
				sprintf(
					'The @deprecated not allowed here. Use %s instead.',
					implode( '/', self::REQUIRED_DEPRECATED_FUNCTIONS )
				),
				$stackPtr,
				'DeprecatedTagNotAllowed'
			);

			return;
		}

		if ( ! empty( $deprecated ) || ! in_array( $tokens[ $stackPtr ]['content'], self::REQUIRED_DEPRECATED_FUNCTIONS, true ) ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'The @deprecated tag is required for the %s hook.',
				$hookName
			),
			$stackPtr,
			'MissDeprecatedTag'
		);
	}

	/**
	 * Process version.
	 *
	 * @since 1.0.0
	 *
	 * @param File   $phpcsFile  The PHP_CodeSniffer file where the token was found.
	 * @param string $hookName   Hook name.
	 * @param array  $deprecated Deprecated tag data.
	 */
	private function processVersion( $phpcsFile, $hookName, $deprecated ) {

		$tokens = $phpcsFile->getTokens();

		if ( ! $this->hasVersion( $deprecated, $tokens ) ) {
			$phpcsFile->addError(
				sprintf(
					'Missing version for @deprecated tag in a comment for the %s hook.',
					$hookName
				),
				$deprecated['tag'],
				'MissDeprecatedVersion'
			);

			return;
		}

		if ( $this->isValidVersion( $deprecated, $tokens ) ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'Invalid version for @deprecated tag in a comment for the %s hook.',
				$hookName
			),
			$deprecated['tag'],
			'InvalidDeprecatedVersion'
		);
	}

	/**
	 * Process empty lines after tag.
	 *
	 * @since 1.0.0
	 *
	 * @param File   $phpcsFile  The PHP_CodeSniffer file where the token was found.
	 * @param string $hookName   Hook name.
	 * @param array  $deprecated Deprecated tag data.
	 */
	private function processLines( $phpcsFile, $hookName, $deprecated ) {

		if (
			$this->isLastTag( $phpcsFile, $deprecated ) ||
			$this->hasEmptyLineAfterInComment( $phpcsFile, $deprecated )
		) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'Add empty line after @deprecated tag for the %s hook.',
				$hookName
			),
			$deprecated['tag'],
			'MissingEmptyLineAfterDeprecated'
		);
	}
}
