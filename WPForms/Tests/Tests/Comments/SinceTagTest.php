<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\SinceTagSniff;

/**
 * Class SinceTagTest.
 *
 * @since 1.0.0
 */
class SinceTagTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new SinceTagSniff() );

		$this->fileHasErrors( $phpcsFile, 'MissingSince', [ 65, 72, 77, 82, 115 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingSinceVersion', [ 120 ] );
		$this->fileHasErrors( $phpcsFile, 'InvalidSinceVersion', [ 97 ] );
		$this->fileHasErrors( $phpcsFile, 'EmptyLineBetweenSinceAndDeprecated', [ 105 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingEmptyLineAfterSince', [ 133 ] );
	}
}
