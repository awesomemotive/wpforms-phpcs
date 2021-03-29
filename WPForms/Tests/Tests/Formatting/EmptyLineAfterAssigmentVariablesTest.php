<?php

namespace WPForms\Tests\Formatting;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Formatting\EmptyLineAfterAssigmentVariablesSniff;

/**
 * Class EmptyLineAfterAssigmentVariablesTest.
 *
 * @since 1.0.0
 */
class EmptyLineAfterAssigmentVariablesTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new EmptyLineAfterAssigmentVariablesSniff() );

		$this->fileHasErrors( $phpcsFile, 'AddEmptyLine', [ 106, 122, 128, 134 ] );
	}
}
