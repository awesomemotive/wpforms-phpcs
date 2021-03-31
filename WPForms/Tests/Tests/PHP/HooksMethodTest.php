<?php

namespace WPForms\Tests\PHP;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\PHP\HooksMethodSniff;

/**
 * Class HooksMethodTest.
 *
 * @since 1.0.0
 */
class HooksMethodTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new HooksMethodSniff() );

		$this->fileHasErrors( $phpcsFile, 'InvalidPlaceForAddingHooks', [ 33, 33, 33, 33, 47, 47, 47, 47 ] );
	}
}
