<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\SinceTagPropertySniff;

/**
 * Class SinceTagPropertiesTest.
 *
 * @since 1.0.0
 */
class SinceTagPropertyTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new SinceTagPropertySniff() );

		$this->fileHasErrors( $phpcsFile, 'MissingPhpDoc', [ 125, 132 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingSinceVersion', [ 83, 92 ] );
		$this->fileHasErrors( $phpcsFile, 'InvalidSinceVersion', [ 137, 146, 155 ] );
		$this->fileHasErrors( $phpcsFile, 'EmptyLineBetweenSinceAndDeprecated', [ 101, 112 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingEmptyLineAfterSince', [ 164 ] );
	}
}
