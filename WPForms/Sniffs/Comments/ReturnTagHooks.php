<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Sniffs\BaseSniff;
use WPForms\Traits\CommentTag;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class ReturnTagHooks.
 *
 * @since 1.0.0
 */
class ReturnTagHooks extends BaseSniff implements Sniff {

	use CommentTag;

	/**
	 * List of hook functions that required `@return` type.
	 *
	 * @since 1.0.0
	 */
	const FILTER_FUNCTIONS = [
		'apply_filters',
		'apply_filters_ref_array',
		'apply_filters_deprecated',
	];

	/**
	 * List of hook functions that non required `@return` type.
	 *
	 * @since 1.0.0
	 */
	const ACTION_FUNCTIONS = [
		'do_action',
		'do_action_ref_array',
		'do_action_deprecated',
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

		$tokens       = $phpcsFile->getTokens();
		$functionName = $tokens[ $stackPtr ]['content'];

		if (
			$tokens[ $stackPtr + 1 ]['code'] !== T_OPEN_PARENTHESIS ||
			(
				! in_array( $functionName, self::FILTER_FUNCTIONS, true ) &&
				! in_array( $functionName, self::ACTION_FUNCTIONS, true )
			)
		) {
			return;
		}

		$commentEnd = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr - 1 );

		if ( ! $commentEnd || $tokens[ $commentEnd ]['line'] !== $tokens[ $stackPtr ]['line'] - 1 ) {
			return;
		}

		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $commentEnd );
		$returnTag    = $this->findTag( '@return', $commentStart, $tokens );

		if ( in_array( $functionName, self::ACTION_FUNCTIONS, true ) ) {
			$this->actionFunctionProcess( $phpcsFile, $returnTag );

			return;
		}

		$this->filterFunctionProcess( $phpcsFile, $returnTag, $stackPtr );
	}

	/**
	 * Process for the action functions.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param array $returnTag Return tag information.
	 */
	private function actionFunctionProcess( $phpcsFile, $returnTag ) {

		if ( empty( $returnTag ) ) {
			return;
		}

		$phpcsFile->addError(
			'The @return tag unnecessary here.',
			$returnTag['tag'],
			'UnnecessaryReturnTag'
		);
	}

	/**
	 * Process for the filter functions.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param array $returnTag Return tag information.
	 * @param int   $stackPtr  Function name position.
	 */
	public function filterFunctionProcess( $phpcsFile, $returnTag, $stackPtr ) {

		$tokens      = $phpcsFile->getTokens();
		$hookNamePtr = $phpcsFile->findNext( T_CONSTANT_ENCAPSED_STRING, $stackPtr + 1 );
		$hookName    = trim( $tokens[ $hookNamePtr ]['content'], '"\'' );

		if ( empty( $returnTag ) ) {
			$phpcsFile->addError(
				sprintf(
					'Add the @return tag for the %s hook.',
					$hookName
				),
				$stackPtr,
				'AddReturnTag'
			);

			return;
		}

		$commentEnd = $phpcsFile->findNext( T_DOC_COMMENT_CLOSE_TAG, $returnTag['tag'] );
		$returnType = $phpcsFile->findNext( T_DOC_COMMENT_STRING, $returnTag['tag'], $commentEnd );

		if ( empty( $returnType ) ) {
			$phpcsFile->addError(
				'Miss return type',
				$returnTag['tag'],
				'MissReturnType'
			);

			return;
		}

		if ( $tokens[ $commentEnd ]['line'] !== $tokens[ $returnTag['tag'] ]['line'] + 1 ) {
			$phpcsFile->addError(
				'The @return tag should be the last tag in the PHPDoc.',
				$returnTag['tag'],
				'InvalidReturnTagPosition'
			);
		}
	}
}
