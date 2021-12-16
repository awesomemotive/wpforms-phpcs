<?php

namespace WPForms\Tests\PHP;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\PHP\ValidateHooksSniff;

/**
 * Class ValidateHooksTest.
 *
 * @since 1.0.0
 */
class ValidateHooksTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since               1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new ValidateHooksSniff() );

		$this->fileHasErrors( $phpcsFile, 'InvalidHookName', [ 8, 9, 10, 11, 13, 14 ] );
	}

	/**
	 * Test process with multi domains.
	 *
	 * @since               1.0.0
	 */
	public function testProcessWithMultiDomains() {

		$phpcsFile = $this->process( new ValidateHooksSniff(), 'MultiDomains' );

		$this->fileHasErrors( $phpcsFile, 'InvalidHookName', [ 3, 4, 5, 6, 13, 14 ] );
	}
}
