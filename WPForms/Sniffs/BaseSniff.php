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
				preg_match( '/(.*[\/\\\][\w.-]+[\/\\\])' . $dir . '[\/\\\]/u', $filePath, $root_path );

				return ! empty( $root_path[1] ) ? $root_path[1] : '';
			}
		}

		return '';
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
	protected function getRelatedPath( $phpcsFile ) {

		$file_path = realpath( $phpcsFile->path );
		$root      = $this->getRootDirectory( $phpcsFile );

		return str_replace( $root, '', $file_path );
	}
}
