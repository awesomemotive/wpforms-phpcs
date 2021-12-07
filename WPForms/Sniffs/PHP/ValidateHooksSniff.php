<?php

namespace WPForms\Sniffs\PHP;

use PHP_CodeSniffer\Config;
use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class ValidateHooksSniff.
 *
 * @since 1.0.0
 */
class ValidateHooksSniff extends BaseSniff implements Sniff {

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

		$hookNamePtr  = $phpcsFile->findNext( T_CONSTANT_ENCAPSED_STRING, $stackPtr + 1 );
		$hookName     = trim( $tokens[ $hookNamePtr ]['content'], '"\'' );
		$expectedName = $this->getExpectedHookName( $phpcsFile );

		if ( 0 === strpos( $hookName, $expectedName ) ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'The %s is invalid hook name. The hook name should start with %s.',
				$hookName,
				$expectedName
			),
			$hookNamePtr,
			'InvalidHookName'
		);
	}

	/**
	 * Get Expected hook name.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 *
	 * @return string
	 */
	private function getExpectedHookName( $phpcsFile ) {

		if (
			empty( $phpcsFile->ruleset ) ||
			empty( $phpcsFile->ruleset->paths[0] )
		) {
			return '';
		}

		$filePath   = $this->getRelatedPath( $phpcsFile );
		$hookPieces = array_filter(
			explode(
				DIRECTORY_SEPARATOR,
				strtolower(
					sprintf(
						'%s_%s',
						pathinfo( $filePath, PATHINFO_DIRNAME ),
						pathinfo( $filePath, PATHINFO_FILENAME )
					)
				)
			)
		);

		if ( Config::getConfigData( 'multi_domains' ) ) {
			array_shift( $hookPieces );
		}

		return implode( '_', $hookPieces );
	}
}
