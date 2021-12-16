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

		$this->fileHasErrors( $phpcsFile, 'InvalidHookName', [ 16, 17, 18, 19, 21, 22 ] );
	}
}
