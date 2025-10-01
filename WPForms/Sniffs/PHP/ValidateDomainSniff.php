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
	 *
	 * @noinspection MagicMethodsValidityInspection
	 */
	public function __set( $name, $value ) {

		$value = explode( ',', $this->normalizeFilename( $value ) );

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
	 * Process this test when one of its tokens is encountered.
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

		$currentDomain  = $this->getCurrentDomain( $phpcsFile, $stackPtr );
		$expectedDomain = $this->getExpectedDomain( $phpcsFile );

		if ( $currentDomain === false ) {
			$phpcsFile->addError(
				'Domain name must be string.',
				$stackPtr,
				'NotStringDomain'
			);

			return;
		}

		if ( ! $currentDomain ) {
			$phpcsFile->addError(
				sprintf(
					"Domain name is not set. You should be using '%s'.",
					$expectedDomain
				),
				$stackPtr,
				'InvalidDomain'
			);

			return;
		}

		if ( ! $expectedDomain ) {
			return;
		}

		if ( $currentDomain === $expectedDomain ) {
			return;
		}

		$phpcsFile->addError(
			sprintf(
				"You are using invalid domain name. Use '%s' instead of '%s'.",
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
	 * @return string|false
	 */
	private function getCurrentDomain( $phpcsFile, $stackPtr ) {

		$tokens   = $phpcsFile->getTokens();
		$openPtr  = $phpcsFile->findNext( T_OPEN_PARENTHESIS, $stackPtr );
		$closePtr = $tokens[ $openPtr ]['parenthesis_closer'];
		$commaPtr = $phpcsFile->findPrevious( T_COMMA, $closePtr, $stackPtr );

		if ( ! $commaPtr ) {
			return '';
		}

		// Find anything except string and whitespace.
		$lastArgument = $phpcsFile->findNext( [ T_CONSTANT_ENCAPSED_STRING, T_WHITESPACE ], $commaPtr + 1, $closePtr, true );

		// The last argument is not a string, but something else.
		if ( $lastArgument ) {
			return false;
		}

		$lastArgument = $phpcsFile->findNext( T_CONSTANT_ENCAPSED_STRING, $commaPtr + 1, $closePtr );

		if ( ! $lastArgument ) {
			return '';
		}

		return strtolower(
			preg_replace( '/[\'\"]/', '', $tokens [ $lastArgument ]['content'] )
		);
	}

	/**
	 * Get domain that should be used for the file.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 *
	 * @return string
	 */
	private function getExpectedDomain( $phpcsFile ) {

		$filePath = $this->getRelativePath( $phpcsFile );
		$root     = $this->getRootDirectory( $phpcsFile );

		if ( ! empty( $this->domains ) ) {
			$currentDomain = $this->findDomainByProperty( $filePath );
		}

		if ( ! empty( $currentDomain ) ) {
			return strtolower( $currentDomain );
		}

		$basename = basename( $root );

		if ( ! Config::getConfigData( 'multi_domains' ) ) {
			return $basename;
		}

		preg_match( '/[\w.-]+/', dirname( $filePath ), $domain );

		return ! empty( $domain[0] ) ? strtolower( $domain[0] ) : $basename;
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

		$fileDir       = DIRECTORY_SEPARATOR . trim( dirname( $filePath ), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
		$currentDomain = '';
		$currentPath   = '';

		foreach ( $this->domains as $domain => $paths ) {
			foreach ( $paths as $path ) {
				$pathDir = DIRECTORY_SEPARATOR . trim( $this->normalizePath( $path ), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

				if (
					0 === strpos( $fileDir, $pathDir ) &&
					strlen( $path ) > strlen( $currentPath )
				) {
					$currentDomain = $domain;
					$currentPath   = $path;
				}
			}
		}

		return $currentDomain;
	}

	/**
	 * Get the normalized path, like realpath() for a non-existing path or file.
	 *
	 * @since 1.0.5
	 *
	 * @param string $path Path to be normalized.
	 *
	 * @return string
	 */
	private function normalizePath( $path ) {

		return (string) array_reduce(
			explode( DIRECTORY_SEPARATOR, $path ),
			static function ( $a, $b ) {
				if ( $a === null ) {
					$a = DIRECTORY_SEPARATOR;
				}
				if ( $b === '' || $b === '.' ) {
					return $a;
				}
				if ( $b === '..' ) {
					return dirname( $a );
				}

				$sep     = DIRECTORY_SEPARATOR;
				$escSep  = '\\' . $sep;
				$pattern = "/$escSep+/";

				return (string) preg_replace( $pattern, DIRECTORY_SEPARATOR, "$a$sep$b" );
			}
		);
	}
}
