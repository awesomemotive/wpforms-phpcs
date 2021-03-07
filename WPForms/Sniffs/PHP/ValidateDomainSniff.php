<?php

namespace WPForms\Sniffs\PHP;

use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class ValidateDomainSniff.
 *
 * @since 1.0.0
 */
class ValidateDomainSniff extends BaseSniff implements Sniff {

	/**
	 * List of translate functions.
	 *
	 * @since 1.0.0
	 */
	const TRANSLATE_FUNCTIONS = [
		'__',
		'_e',
		'_x',
		'_n',
		'_ex',
		'_nx',
		'esc_html__',
		'esc_html_e',
		'esc_html_x',
		'esc_attr__',
		'esc_attr_e',
		'esc_attr_x',
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

		if ( ! in_array( $tokens[ $stackPtr ]['content'], self::TRANSLATE_FUNCTIONS, true ) || $tokens[ $stackPtr + 1 ]['code'] !== T_OPEN_PARENTHESIS ) {
			return;
		}

		$last_argument  = $phpcsFile->findPrevious( T_CONSTANT_ENCAPSED_STRING, $phpcsFile->findNext( T_CLOSE_PARENTHESIS, $stackPtr ) );
		$current_domain = preg_replace( '/[\'\"]/', '', $tokens [ $last_argument ]['content'] );

		preg_match( '/.*[\/\\\]plugins[\/\\\]([\w.-]+)[\/\\\]/u', $phpcsFile->path, $correct_domain );

		if ( empty( $correct_domain[1] ) ) {
			return;
		}

		$correct_domain = $correct_domain[1];

		if ( $correct_domain === 'wpforms' ) {
			preg_match( '/.*[\/\\\]' . $correct_domain . '[\/\\\](.*)/u', $phpcsFile->path, $plugin_path );
			$plugin_path = $plugin_path[1];

			$correct_domain = 0 === strpos( $plugin_path, 'pro/' ) ||
				0 === strpos( $plugin_path, 'src/Pro/' ) ||
				0 === strpos( $plugin_path, 'pro\\' ) ||
				0 === strpos( $plugin_path, 'src\\Pro\\' ) ?
				'wpforms' :
				'wpforms-lite';
		}

		if ( $current_domain === $correct_domain ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'You are using invalid domain name. Use %s instead of %s',
				$correct_domain,
				$current_domain
			),
			$stackPtr,
			'InvalidDomain'
		);
	}
}
