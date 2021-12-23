<?php

namespace WPForms\Tests\Formatting;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Formatting\EmptyLineBeforeReturnSniff;

/**
 * Class EmptyLineBeforeReturnSniffTest.
 *
 * @since 1.0.0
 */
class EmptyLineBeforeReturnSniffTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new EmptyLineBeforeReturnSniff() );

		$this->fileHasErrors( $phpcsFile, 'AddEmptyLineBeforeReturnStatement', [ 108, 121, 140, 145, 152 ] );
		$this->fileHasErrors( $phpcsFile, 'RemoveEmptyLineBeforeReturnStatement', [ 135, 150, 161 ] );
	}
}
