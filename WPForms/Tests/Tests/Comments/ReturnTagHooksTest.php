<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\ReturnTagHooks;

/**
 * Class ReturnTagHooksTest.
 *
 * @since 1.0.0
 */
class ReturnTagHooksTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new ReturnTagHooks() );

		$this->fileHasErrors( $phpcsFile, 'UnnecessaryReturnTag', [ 80 ] );
		$this->fileHasErrors( $phpcsFile, 'AddReturnTag', [ 106 ] );
		$this->fileHasErrors( $phpcsFile, 'MissReturnType', [ 130 ] );
		$this->fileHasErrors( $phpcsFile, 'InvalidReturnTagPosition', [ 148 ] );
	}
}
