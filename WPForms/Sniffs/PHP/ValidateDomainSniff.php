<?php

namespace WPForms\Sniffs\PHP;

use PHP_CodeSniffer\Config;
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
	 * List of rewritten domains.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $domains = [];

	/**
	 * List of translate functions.
	 *
	 * @since 1.0.0
	 */
	const TRANSLATE_FUNCTIONS = [ // phpcs:ignore PHPCompatibility.InitialValue.NewConstantArraysUsingConst.Found
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
	 * Rewrite some path for some domains via sniff properties.
	 *
	 * Example:
	 * <rule ref="WPForms.PHP.ValidateDomain">
	 *      <properties>
	 *          <property name="wpforms-lite" value="wpforms"/>
	 *          <property name="wpforms" value="wpforms/pro/,wpforms/src/Pro/"/>
	 *      </properties>
	 * </rule>.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name  Property name.
	 * @param mixed  $value Value.
	 */
	public function __set( $name, $value ) {

		$value = explode( ',', $value );

		$this->domains[ strtolower( $name ) ] = $value;
	}

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

		if ( $tokens[ $stackPtr + 1 ]['code'] !== T_OPEN_PARENTHESIS || ! in_array( $tokens[ $stackPtr ]['content'], self::TRANSLATE_FUNCTIONS, true ) ) {
			return;
		}

		$currentDomain  = $this->getCurrentDomains( $phpcsFile, $stackPtr );
		$expectedDomain = $this->getExpectedDomain( $phpcsFile );

		if ( ! $expectedDomain ) {
			return;
		}

		if ( $currentDomain === $expectedDomain ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'You are using invalid domain name. Use %s instead of %s',
				$expectedDomain,
				$currentDomain
			),
			$stackPtr,
			'InvalidDomain'
		);
	}

	/**
	 * Get current domain.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 *
	 * @return string
	 */
	private function getCurrentDomains( $phpcsFile, $stackPtr ) {

		$tokens       = $phpcsFile->getTokens();
		$lastArgument = $phpcsFile->findPrevious(
			T_CONSTANT_ENCAPSED_STRING,
			$phpcsFile->findNext( T_CLOSE_PARENTHESIS, $stackPtr )
		);

		return strtolower(
			preg_replace( '/[\'\"]/', '', $tokens [ $lastArgument ]['content'] )
		);
	}

	/**
	 * Get domain that should be use for the file.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 *
	 * @return string
	 */
	private function getExpectedDomain( $phpcsFile ) {

		$filePath = $this->getRelatedPath( $phpcsFile );
		$root     = $this->getRootDirectory( $phpcsFile );

		if ( ! empty( $this->domains ) ) {
			$currentDomain = $this->findDomainByProperty( $filePath );
		}

		if ( ! empty( $currentDomain ) ) {
			return strtolower( $currentDomain );
		}

		if ( ! Config::getConfigData( 'multi_domains' ) ) {
			return basename( $root );
		}

		preg_match( '/([\w.-]+)/', $filePath, $domain );

		return ! empty( $domain[0] ) ? strtolower( $domain[0] ) : '';
	}

	/**
	 * Get domain by properties from ruleset.xml.
	 *
	 * @since 1.0.0
	 *
	 * @param string $filePath File path.
	 *
	 * @return string
	 */
	private function findDomainByProperty( $filePath ) {

		$currentDomain = '';
		$currentPath   = '';

		foreach ( $this->domains as $domain => $paths ) {
			foreach ( $paths as $path ) {
				if (
					0 === strpos( $filePath, $path ) &&
					strlen( $path ) > strlen( $currentPath )
				) {
					$currentDomain = $domain;
					$currentPath   = $path;
				}
			}
		}

		return $currentDomain;
	}
}
