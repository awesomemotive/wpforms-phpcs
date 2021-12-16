<?php

namespace WPForms\Tests;

// phpcs:ignore WPForms.PHP.UseStatement.UnusedUseStatement
use PHP_CodeSniffer\Exceptions\DeepExitException;
use ReflectionClass;
use RuntimeException;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\LocalFile;

/**
 * Class TestCase.
 *
 * @since 1.0.0
 */
class TestCase extends \PHPUnit\Framework\TestCase {

	/**
	 * Set up test.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 * @throws DeepExitException DeepExitException.
	 */
	public function setUp() {

		parent::setUp();

		$config = new Config();

		$config::setConfigData( 'multi_domains', null );
	}

	/**
	 * Process file with exact sniff.
	 *
	 * @since 1.0.0
	 *
	 * @param Sniff  $className   Class name.
	 * @param string $rulesetName Ruleset name from the Tests/TestedRulesets directory.
	 *
	 * @return LocalFile
	 *
	 * @throws RuntimeException Tested file doesn't exist.
	 */
	protected function process( Sniff $className, $rulesetName = '' ) {

		$class         = new ReflectionClass( $className );
		$classFileName = $this->normalizeFilename( $class->getFileName() );
		$localFile     = WPFORMS_TESTED_FILES_PATH . str_replace( 'Sniff.php', '.php', str_replace( WPFORMS_SNIFFS_PATH, '', $classFileName ) );

		if ( ! file_exists( $localFile ) ) {
			throw new RuntimeException(
				sprintf(
					'The %s file does not exist',
					$localFile
				)
			);
		}

		$rulesetName = $rulesetName ? 'TestedRulesets/' . $rulesetName . '/ruleset.xml' : 'TestedRulesets/Default/ruleset.xml';
		$rulesetName = realpath( $this->normalizeFilename( WPFORMS_TESTS_PATH . $rulesetName ) );
		$config      = new Config( [ '--standard=' . $rulesetName ] );
		$ruleset     = new Ruleset( $config );
		$sniffs      = $ruleset->sniffs;

		$ruleset->registerSniffs( [ $classFileName ], [], [] );
		$ruleset->sniffs = array_merge( $ruleset->sniffs, $sniffs );

		$ruleset->populateTokenListeners();

		$phpcsFile = new LocalFile(
			$localFile,
			$ruleset,
			$config
		);

		$phpcsFile->process();

		return $phpcsFile;
	}

	/**
	 * Tested files has exact sniff error in lines.
	 *
	 * @since 1.0.0
	 *
	 * @param LocalFile $phpcsFile Tested file.
	 * @param string    $name      Sniff error name.
	 * @param array     $lines     List of expected lines with error.
	 */
	protected function fileHasErrors( LocalFile $phpcsFile, $name, $lines ) { // phpcs:ignore Generic.Metrics.NestingLevel.MaxExceeded

		$errors = [];

		foreach ( $phpcsFile->getErrors() as $line => $groupErrors ) {
			foreach ( $groupErrors as $sniffErrors ) {
				foreach ( $sniffErrors as $error ) {
					if ( preg_match( '/\.' . $name . '$/', $error['source'] ) ) {
						$errors[] = $line;
					}
				}
			}
		}

		sort( $errors );

		self::assertEquals( $lines, $errors );
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
}
