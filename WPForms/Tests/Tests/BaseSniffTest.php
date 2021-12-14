<?php

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
// phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
// phpcs:disable WPForms.PHP.UseStatement.UnusedUseStatement

namespace WPForms\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use ReflectionClass;
use ReflectionException;
use WPForms\Sniffs\BaseSniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class BaseSniffTestInstance.
 *
 * @since 1.0.0
 */
class BaseSniffTestInstance extends BaseSniff {

	/**
	 * Get relative path from project root directory.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 *
	 * @return string
	 */
	public function getRelativePath( $phpcsFile ) {

		return parent::getRelativePath( $phpcsFile );
	}
}

/**
 * Class BaseSniffTest.
 *
 * @since 1.0.0
 */
class BaseSniffTest extends TestCase {

	/**
	 * Test getRelativePath.
	 *
	 * @since 1.0.0
	 *
	 * @throws ReflectionException ReflectionException.
	 */
	public function testGetRelativePath() {

		$className     = BaseSniff::class;
		$class         = new ReflectionClass( $className );
		$classFileName = $this->normalizeFilename( $class->getFileName() );

		$rulesetName = '';
		$rulesetName = $rulesetName ? 'TestedRulesets/' . $rulesetName . '/ruleset.xml' : 'TestedRulesets/Default/ruleset.xml';
		$rulesetName = realpath( $this->normalizeFilename( WPFORMS_TESTS_PATH . $rulesetName ) );
		$config      = new Config( [ '--standard=' . $rulesetName ] );
		$ruleset     = new Ruleset( $config );
		$phpcsFile   = new File(
			$classFileName,
			$ruleset,
			$config
		);

		$subject = new BaseSniffTestInstance();

		$relativePath = $subject->getRelativePath( $phpcsFile );

		self::assertSame( $classFileName, WPFORMS_ROOT_PATH . $relativePath );

		$classFileName = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'PHP_CodeSniffertemp_folder0000' . DIRECTORY_SEPARATOR . $relativePath;

		if ( file_exists( $classFileName ) ) {
			unlink( $classFileName );
		}

		$this->mkdirRecursive( $classFileName );

		$phpcsFile = new File(
			$classFileName,
			$ruleset,
			$config
		);

		self::assertSame( $relativePath, $subject->getRelativePath( $phpcsFile ) );
	}

	/**
	 * Make directory recursive.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path Path.
	 *
	 * @return void
	 */
	private function mkdirRecursive( $path ) {

		$str = explode( DIRECTORY_SEPARATOR, $path );
		$dir = '';

		foreach ( $str as $index => $part ) {
			if ( $index === 0 ) {
				$dir = $part;
			} else {
				$dir .= DIRECTORY_SEPARATOR . $part;
			}

			if ( $dir !== '' && ! is_dir( $dir ) && strpos( $dir, '.' ) === false ) {
				mkdir( $dir );
			} elseif ( ! file_exists( $dir ) && strpos( $dir, '.' ) !== false ) {
				touch( $dir );
			}
		}
	}
}
