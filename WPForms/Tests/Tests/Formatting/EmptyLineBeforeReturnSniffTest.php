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
	public function test_process() {

		$phpcsFile = $this->process( new EmptyLineBeforeReturnSniff() );
		$this->fileHasErrors( $phpcsFile, 'AddEmptyLineBeforeReturnStatement', [ 99, 112, 131, 136, 143 ] );
		$this->fileHasErrors( $phpcsFile, 'RemoveEmptyLineBeforeReturnStatement', [ 126, 141 ] );
	}
}
