<?php

namespace WPForms\Tests\PHP;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\PHP\SwitchSniff;

/**
 * Class SwitchTest.
 *
 * @since 1.0.0
 */
class SwitchTest extends TestCase {

	/**
	 * Test process without options.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new SwitchSniff() );

		$this->fileHasErrors( $phpcsFile, 'AddEmptyLineBefore', [ 46, 64, 51, 57, 61 ] );
		$this->fileHasErrors( $phpcsFile, 'RemoveEmptyLineBefore', [ 47, 50 ] );
	}
}
