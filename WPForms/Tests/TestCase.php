<?php

namespace WPForms\Tests;

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
	 * Process file with exact sniff.
	 *
	 * @since 1.0.0
	 *
	 * @param Sniff  $className   Class name.
	 * @param string $rulesetName Ruleset name from the Tests/TestedRulesets directory.
	 *
	 * @return LocalFile
	 *
	 * @throws RuntimeException Tested file doesn't exists.
	 */
	protected function process( Sniff $className, $rulesetName = '' ) {

		$class     = new ReflectionClass( $className );
		$localFile = WPFORMS_TESTED_FILES_PATH . str_replace( 'Sniff.php', '.php', str_replace( WPFORMS_SNIFFS_PATH, '', $class->getFileName() ) );

		if ( ! file_exists( $localFile ) ) {
			throw new RuntimeException(
				sprintf(
					'The %s file doesn\'t exist',
					$localFile
				)
			);
		}

		$rulesetName = $rulesetName ? 'WPForms/Tests/TestedRulesets/' . $rulesetName . '/ruleset.xml' : 'WPForms/Tests/TestedRulesets/Default/ruleset.xml';
		$config      = new Config( [ '--standard=' . $rulesetName ] );
		$ruleset     = new Ruleset( $config );

		$ruleset->registerSniffs( [ $class->getFileName() ], [], [] );
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

		self::assertEquals( $errors, $lines );
	}
}
