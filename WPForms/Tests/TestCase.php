<?php

namespace WPForms\Tests;

use Exception;
use ReflectionClass;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\LocalFile;

class TestCase extends \PHPUnit\Framework\TestCase {

	protected function process( Sniff $className ) {

		$class     = new ReflectionClass( $className );
		$localFile = WPFORMS_TESTED_FILES_PATH . str_replace( 'Sniff.php', '.php', str_replace( WPFORMS_SNIFFS_PATH, '', $class->getFileName() ) );

		if ( ! file_exists( $localFile ) ) {
			throw new Exception(
				sprintf(
					'The %s file doesn\'t exist',
					$localFile
				)
			);
		}

		$config  = new Config();
		$ruleset = new Ruleset( $config );
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

	protected function fileHasErrors( LocalFile $phpcsFile, string $name, array $lines ) {

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
		$this->assertEquals( $errors, $lines );
	}
}
