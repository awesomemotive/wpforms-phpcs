<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use WPForms\Traits\Description;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Traits\DuplicateHook;

/**
 * Class ParamTagHooksSniff.
 *
 * @since 1.0.0
 */
class ParamTagHooksSniff extends BaseSniff implements Sniff {

	use DuplicateHook;
	use Description;

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

		$tokens       = $phpcsFile->getTokens();
		$commentEnd   = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr );
		$commentStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $commentEnd );

		if ( $this->isDuplicateHook( $commentStart, $tokens ) ) {
			return;
		}

		$params            = $this->findTags( '@param', $commentStart, $tokens );
		$argumentsQuantity = $this->countArguments( $phpcsFile, $stackPtr );

		if ( count( $params ) !== $argumentsQuantity ) {
			$phpcsFile->addError(
				sprintf(
					'You should have %d @param tags',
					$argumentsQuantity
				),
				$stackPtr,
				'InvalidParamTagsQuantity'
			);

			return;
		}

		$this->processParams( $phpcsFile, $params );
	}

	/**
	 * Get arguments quantity.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 *
	 * @return int
	 */
	private function countArguments( $phpcsFile, $stackPtr ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$tokens          = $phpcsFile->getTokens();
		$openParenthesis = $phpcsFile->findNext( [ T_OPEN_PARENTHESIS ], $stackPtr + 1 );
		$lastPosition    = $tokens[ $openParenthesis ]['parenthesis_closer'] - 1;
		$currentPosition = $phpcsFile->findNext( T_COMMA, $stackPtr + 1 ) + 1;
		$quantity        = 0;

		while ( $currentPosition < $lastPosition ) {
			if ( in_array( $tokens[ $currentPosition ]['code'], [ T_WHITESPACE, T_COMMA ], true ) ) {
				$currentPosition ++;
				continue;
			}

			$quantity ++;

			if ( ! empty( $tokens[ $currentPosition ]['bracket_closer'] ) ) {
				$currentPosition = $tokens[ $currentPosition ]['bracket_closer'] + 2;

				continue;
			}

			if ( ! empty( $tokens[ $currentPosition ]['scope_closer'] ) ) {
				$currentPosition = $tokens[ $currentPosition ]['scope_closer'] + 2;

				continue;
			}

			if (
				$tokens[ $currentPosition ]['code'] !== T_STATIC &&
				$tokens[ $currentPosition + 1 ]['code'] !== T_OBJECT_OPERATOR
			) {
				$currentPosition ++;

				continue;
			}

			if ( $tokens[ $currentPosition + 3 ]['code'] === T_COMMA ) {
				$currentPosition += 4;

				continue;
			}

			if ( ! empty( $tokens[ $currentPosition + 3 ]['bracket_closer'] ) ) {
				$currentPosition = $tokens[ $currentPosition + 3 ]['bracket_closer'] + 1;

				continue;
			}

			if ( ! empty( $tokens[ $currentPosition + 3 ]['parenthesis_closer'] ) ) {
				$currentPosition = $tokens[ $currentPosition + 3 ]['parenthesis_closer'] + 1;

				continue;
			}

			$currentPosition++;
		}

		return $quantity;
	}

	/**
	 * Process params tag.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param array $params    List of params tags.
	 *
	 * @return array
	 */
	private function processParams( $phpcsFile, $params ) {

		$typeLength = 0;
		$varLength  = 0;

		foreach ( $params as $key => $param ) {
			$param          = $this->processParamTag( $phpcsFile, $param );
			$params[ $key ] = $param;
			$typeLength     = max( $typeLength, strlen( $param['elements'][0] ) );
			$varLength      = max( $varLength, strlen( $param['elements'][0] ) + strlen( $param['elements'][1] ) );
		}

		foreach ( $params as $param ) {
			if (
				$typeLength !== strlen( $param['elements'][0] ) ||
				$varLength !== strlen( $param['elements'][0] ) + strlen( $param['elements'][1] )
			) {
				$phpcsFile->addError(
					'You should align the params types, variables, and short description',
					$param['tag'],
					'InvalidAlign'
				);
			}
		}

		return $params;
	}

	/**
	 * Process the param tag.
	 *
	 * @since 1.0.0
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param array $param     List of params tags.
	 *
	 * @return array
	 */
	private function processParamTag( $phpcsFile, $param ) {

		$tokens            = $phpcsFile->getTokens();
		$commentString     = $phpcsFile->findNext( T_DOC_COMMENT_STRING, $param['tag'] + 1 );
		$param['elements'] = [ '', '', '' ];

		if ( $tokens[ $commentString ]['line'] !== $param['line'] ) {
			$phpcsFile->addError(
				'Missing type, variable, and short description',
				$param['tag'],
				'MissParamInfo'
			);

			return $param;
		}

		preg_match( '/([\w\s]+)(\$[\w_]+\s+)(.*)/', $tokens[ $commentString ]['content'], $paramElements );

		if ( empty( $paramElements[1] ) || empty( $paramElements[2] ) || empty( $paramElements[3] ) ) {
			$phpcsFile->addError(
				'Missing type, variable, or short description',
				$param['tag'],
				'MissParamInfo'
			);

			return $param;
		}

		$param['elements'] = [ $paramElements[1], $paramElements[2], $paramElements[3] ];

		if ( $tokens[ $param['tag'] + 1 ]['code'] === T_DOC_COMMENT_WHITESPACE && strlen( $tokens[ $param['tag'] + 1 ]['content'] ) > 1 ) {
			$phpcsFile->addError(
				'Allow only 1 space after the @param tag.',
				$param['tag'],
				'ExtraSpacesAfterParamTag'
			);
		}

		$last = $this->findLastDescriptionLIne( $phpcsFile, $commentString, $tokens );

		if ( ! $this->hasStopSymbol( $last, $tokens ) ) {
			$phpcsFile->addError(
				'You should add a stop symbol for the short description.',
				$last,
				'AddStopSymbol'
			);
		}

		return $param;
	}
}
