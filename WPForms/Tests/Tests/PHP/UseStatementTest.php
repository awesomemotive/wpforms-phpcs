<?php

namespace WPForms\Tests\PHP;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\PHP\UseStatementSniff;

/**
 * Class UseStatementTest.
 *
 * @since 1.0.0
 */
class UseStatementTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new UseStatementSniff() );

		$this->fileHasErrors( $phpcsFile, 'UnusedUseStatement', [ 12, 13, 16 ] );
	}
}
