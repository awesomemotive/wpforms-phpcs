<?php

namespace WPForms\Sniffs;

use PHP_CodeSniffer\Files\File;

/**
 * Class BaseSniff.
 *
 * @since 1.1.0
 */
abstract class BaseSniff {

	/**
	 * Log into the console. Accepts any number of vars.
	 *
	 * @since 1.1.0
	 */
	protected function log() {

		foreach ( func_get_args() as $var ) {
			echo print_r( $var, true ) . PHP_EOL; // phpcs:ignore
		}
	}

	/**
	 * Get project root directory.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 *
	 * @return string
	 */
	protected function getRootDirectory( $phpcsFile ) {

		if (
			empty( $phpcsFile->ruleset ) ||
			empty( $phpcsFile->ruleset->paths[0] )
		) {
			return '';
		}

		$filePath = realpath( $phpcsFile->ruleset->paths[0] );

		if ( 'ruleset.xml' !== basename( $filePath ) ) {
			return dirname( $filePath );
		}

		foreach (
			[
				'.vendor',
				'vendor',
				'.packages',
				'packages',
				'WPForms', // For tests.
			] as $dir
		) {
			if ( false !== stripos( $filePath, $dir ) ) {
				preg_match( '/(.*[\/\\\][\w.-]+[\/\\\])' . $dir . '[\/\\\]/u', $filePath, $rootPath );

				return ! empty( $rootPath[1] ) ? $rootPath[1] : '';
			}
		}

		return '';
	}

	/**
	 * Get a relative path from project root directory.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 *
	 * @return string
	 */
	protected function getRelativePath( $phpcsFile ) {

		$filePath = realpath( $phpcsFile->path );
		$root     = $this->getRootDirectory( $phpcsFile );

		$csFolder = 'php_codesniffertemp_folder';

		if ( stripos( $filePath, $csFolder ) !== false ) {
			// Special case for processing temp file in the PhpStorm temp folder.
			$filePath = preg_replace( '#.+[/\\\]' . $csFolder . '\d+[/\\\]#i', '', $filePath );

			$rootArr = array_filter( explode( DIRECTORY_SEPARATOR, $root ) );

			$count = count( $rootArr );

			for ( $i = 0; $i < $count; $i++ ) {
				$rootPart = implode( DIRECTORY_SEPARATOR, array_slice( $rootArr, $i ) );

				if ( strpos( $filePath, $rootPart ) === 0 ) {
					return str_replace( $rootPart, '', $filePath );
				}
			}

			return $filePath;
		}

		return str_replace( $root, '', $filePath );
	}

	/**
	 * Return filename with current OS directory separators.
	 *
	 * @since 1.0.0
	 *
	 * @param string $filename Filename.
	 *
	 * @return string
	 */
	protected function normalizeFilename( $filename ) {

		return str_replace( '/', DIRECTORY_SEPARATOR, $filename );
	}

	/**
	 * Get the first argument of a function.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 *
	 * @return string
	 */
	protected function getFirstArgument( $phpcsFile, $stackPtr ) {

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
	 * Check whether the token is break in the switch statement.
	 *
	 * @since 1.0.4
	 *
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param array $token     First token on the next line.
	 *
	 * @return bool
	 */
	protected function isBreakInSwitch( $phpcsFile, $token ) {

		$tokens = $phpcsFile->getTokens();

		if ( $token['code'] !== T_BREAK ) {
			return false;
		}

		return (
			isset( $token['scope_condition'] ) &&
			in_array( $tokens[ $token['scope_condition'] ]['code'], [ T_CASE, T_DEFAULT ], true )
		);
	}

	/**
	 * Get a fully qualified class name.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 *
	 * @return string
	 */
	protected function getFullyQualifiedClassName( $phpcsFile ) {

		$namespace    = '';
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

		if ( $classPtr === false ) {
			return '';
		}

		$classEnd = $phpcsFile->findNext(
			[ T_EXTENDS, T_IMPLEMENTS, 'PHPCS_T_OPEN_CURLY_BRACKET' ],
			$classPtr + 1
		);

		$class = trim( $phpcsFile->getTokensAsString( $classPtr + 1, $classEnd - $classPtr - 1 ) );
		$class = $this->convertClassName( $class );

		$fqcn = $this->camelToSnake( str_replace( '\\', '', $namespace ) ) . '_' . $class;

		// Fix WPForms.
		// Remove _plugin for the main plugin class, which is usually Plugin.
		$fqcn = str_replace( [ 'wp_forms', '_plugin' ], [ 'wpforms', '' ], $fqcn );

		$fqcnArr = explode( '_', $fqcn );

		$prevItem = '';

		// Remove repetitions.
		foreach ( $fqcnArr as $key => $item ) {
			if ( $prevItem === $item ) {
				unset( $fqcnArr[ $key ] );
			}

			$prevItem = $item;
		}

		return implode( '_', $fqcnArr );
	}

	/**
	 * Convert class name to snake case.
	 *
	 * @since 1.0.2
	 *
	 * @param string $className Class name.
	 *
	 * @return string
	 */
	private function convertClassName( $className ) {

		if ( strpos( $className, '_' ) !== false ) {
			return strtolower( $className );
		}

		return $this->camelToSnake( $className );
	}

	/**
	 * Convert string from CamelCase to snake_case.
	 *
	 * @since 1.0.2
	 *
	 * @param string $s A string.
	 *
	 * @return string
	 */
	private function camelToSnake( $s ) {

		return strtolower( preg_replace( [ '/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/' ], '$1_$2', $s ) );
	}
}
