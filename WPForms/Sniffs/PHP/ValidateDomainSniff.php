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
	 * List of rewritten domains.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $domains = [];

	/**
	 * Multi-domains mode. The current domain is the directory name that next after project root.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public $multi_domains = false;

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
	 *          <property name="multi_domains" value="true"/>
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

		if ( $name !== 'multi_domains' ) {
			$value = explode( ',', $value );

			$this->domains[ strtolower( $name ) ] = $value;

			return;
		}

		$this->{$name} = (bool) $value;
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

		$current_domain  = $this->getCurrentDomains( $phpcsFile, $stackPtr );
		$expected_domain = $this->getExpectedDomain( $phpcsFile );

		if ( ! $expected_domain ) {
			return;
		}

		if ( $current_domain === $expected_domain ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				'You are using invalid domain name. Use %s instead of %s',
				$expected_domain,
				$current_domain
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

		$tokens        = $phpcsFile->getTokens();
		$last_argument = $phpcsFile->findPrevious(
			T_CONSTANT_ENCAPSED_STRING,
			$phpcsFile->findNext( T_CLOSE_PARENTHESIS, $stackPtr )
		);

		return strtolower(
			preg_replace( '/[\'\"]/', '', $tokens [ $last_argument ]['content'] )
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

		if (
			empty( $phpcsFile->ruleset ) ||
			empty( $phpcsFile->ruleset->paths[0] )
		) {
			return '';
		}

		$path      = realpath( $phpcsFile->ruleset->paths[0] );
		$file_path = realpath( $phpcsFile->path );
		$root      = 'ruleset.xml' === basename( $path ) ?
			$this->getRootDirectory( $path ) :
			dirname( $path );

		$file_path = str_replace( $root, '', $file_path );

		if ( ! empty( $this->domains ) ) {
			$current_domain = $this->findDomainByProperty( $file_path );
		}

		if ( ! empty( $current_domain ) ) {
			return strtolower( $current_domain );
		}

		if ( ! $this->multi_domains ) {
			return basename( $root );
		}

		preg_match( '/([\w.-]+)/', $file_path, $domain );

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

		$current_domain = '';
		$current_path   = '';

		foreach ( $this->domains as $domain => $paths ) {
			foreach ( $paths as $path ) {
				if (
					0 === strpos( $filePath, $path ) &&
					strlen( $path ) > strlen( $current_path )
				) {
					$current_domain = $domain;
					$current_path   = $path;
				}
			}
		}

		return $current_domain;
	}

	/**
	 * Get project root directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $filePath File path.
	 *
	 * @return string
	 */
	private function getRootDirectory( $filePath ) {

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
}
