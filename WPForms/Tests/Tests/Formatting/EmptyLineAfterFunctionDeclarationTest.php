<?php

namespace WPForms\Tests\Formatting;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Formatting\EmptyLineAfterFunctionDeclarationSniff;

/**
 * Class EmptyLineAfterFunctionDeclarationTest.
 *
 * @since 1.0.0
 */
class EmptyLineAfterFunctionDeclarationTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function test_process() {

		$phpcsFile = $this->process( new EmptyLineAfterFunctionDeclarationSniff() );
		$this->fileHasErrors( $phpcsFile, 'AddEmptyLineAfterFunctionDeclaration', [ 71, 82, 98, 109 ] );
	}
}
