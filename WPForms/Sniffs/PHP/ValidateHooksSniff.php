<?php

namespace WPForms\Sniffs\PHP;

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

		$hookName     = $this->getFirstArgument( $phpcsFile, $stackPtr );
		$expectedName = $this->getFullyQualifiedClassName( $phpcsFile );

		if ( ! $expectedName || 0 === strpos( $hookName, $expectedName ) ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'The `%s` is invalid hook name. The hook name should start with `%s`.',
				$hookName,
				$expectedName
			),
			$stackPtr,
			'InvalidHookName'
		);
	}

	/**
	 * Get first argument of a function.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 *
	 * @return string
	 */
	private function getFirstArgument( $phpcsFile, $stackPtr ) {

		$blankTokens = [ T_WHITESPACE, T_COMMENT, T_PHPCS_IGNORE ];

		$tokens   = $phpcsFile->getTokens();
		$openPtr  = $phpcsFile->findNext( T_OPEN_PARENTHESIS, $stackPtr );
		$closePtr = $tokens[ $openPtr ]['parenthesis_closer'];
		$commaPtr = $phpcsFile->findNext( T_COMMA, $openPtr, $closePtr );

		if ( $commaPtr ) {
			$closePtr = $commaPtr;
		}

		$openPtr  = $phpcsFile->findNext( $blankTokens, $openPtr + 1, $closePtr, true );
		$closePtr = $phpcsFile->findPrevious( $blankTokens, $closePtr - 1, $openPtr, true );

		$firstArgument = trim( $phpcsFile->getTokensAsString( $openPtr, $closePtr - $openPtr + 1 ) );

		return strtolower(
			preg_replace( '/[\'\"]/', '', $firstArgument )
		);
	}

	/**
	 * Get fully qualified class name.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 *
	 * @return string
	 */
	private function getFullyQualifiedClassName( $phpcsFile ) {

		$namespace    = '';
		$class        = '';
		$stackPtr     = 0;
		$namespacePtr = $phpcsFile->findNext( T_NAMESPACE, $stackPtr );

		if ( $namespacePtr !== false ) {
			$nsEnd = $phpcsFile->findNext(
				[ T_NS_SEPARATOR, T_STRING, T_WHITESPACE ],
				$namespacePtr + 1,
				null,
				true
			);

			$namespace = trim( $phpcsFile->getTokensAsString( $namespacePtr + 1, $nsEnd - $namespacePtr - 1 ) );
			$stackPtr  = $nsEnd;
		}

		$classPtr = $phpcsFile->findNext( T_CLASS, $stackPtr );

		if ( $classPtr !== false ) {
			$classEnd = $phpcsFile->findNext(
				[ T_EXTENDS, T_IMPLEMENTS, 'PHPCS_T_OPEN_CURLY_BRACKET' ],
				$classPtr + 1
			);

			$class = trim( $phpcsFile->getTokensAsString( $classPtr + 1, $classEnd - $classPtr - 1 ) );
			$class = $this->convertClassName( $class );
		}

		return strtolower( str_replace( '\\', '_', $namespace ? $namespace . '\\' . $class : $class ) );
	}

	/**
	 * Convert class name to snake case.
	 *
	 * @since {VERSION}
	 *
	 * @param string $class Class name.
	 *
	 * @return string
	 */
	private function convertClassName( $class ) {

		if ( strpos( $class, '_' ) !== false ) {
			return $class;
		}

		return $this->camelToSnake( $class );
	}

	/**
	 * Convert string from CamelCase to snake_case.
	 *
	 * @since {VERSION}
	 *
	 * @param string $string A string.
	 *
	 * @return string
	 */
	private function camelToSnake( $string ) {

		return strtolower( preg_replace( [ '/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/' ], '$1_$2', $string ) );
	}
}
