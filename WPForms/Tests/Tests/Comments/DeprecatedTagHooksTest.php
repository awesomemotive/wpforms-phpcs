<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\DeprecatedTagHooksSniff;

/**
 * Class DeprecatedTagHooksTest.
 *
 * @since 1.0.0
 */
class DeprecatedTagHooksTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new DeprecatedTagHooksSniff() );

		$this->fileHasErrors( $phpcsFile, 'MissDeprecatedTag', [ 18 ] );
		$this->fileHasErrors( $phpcsFile, 'DeprecatedTagNotAllowed', [ 26 ] );
		$this->fileHasErrors( $phpcsFile, 'MissDeprecatedVersion', [ 32 ] );
		$this->fileHasErrors( $phpcsFile, 'InvalidDeprecatedVersion', [ 40 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingEmptyLineAfterDeprecated', [ 48 ] );
	}
}
