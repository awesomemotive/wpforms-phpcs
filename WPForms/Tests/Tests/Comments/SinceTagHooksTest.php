<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\SinceTagHooksSniff;

/**
 * Class HooksPHPDocTest.
 *
 * @since 1.0.0
 */
class SinceTagHooksTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new SinceTagHooksSniff() );

		$this->fileHasErrors( $phpcsFile, 'MissSinceTag', [ 77, 99 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingSinceVersion', [ 115 ] );
		$this->fileHasErrors( $phpcsFile, 'InvalidSinceVersion', [ 139 ] );
		$this->fileHasErrors( $phpcsFile, 'EmptyLineBetweenSinceAndDeprecated', [ 163, 189 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingEmptyLineAfterSince', [ 215 ] );
	}
}
