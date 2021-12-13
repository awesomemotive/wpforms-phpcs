<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\LanguageInjectionSniff;

/**
 * Class LanguageInjectionTest.
 *
 * @since 1.0.0
 */
class LanguageInjectionTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new LanguageInjectionSniff(), 'LanguageInjection' );

		$this->fileHasErrors( $phpcsFile, 'InvalidEndChar', [ 6 ] );
		$this->fileHasErrors( $phpcsFile, 'SpacingBefore', [ 6 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingShort', [ 11 ] );
	}
}
