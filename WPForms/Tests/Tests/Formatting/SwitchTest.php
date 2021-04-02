<?php

namespace WPForms\Tests\Formatting;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Formatting\SwitchSniff;

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

		$this->fileHasErrors( $phpcsFile, 'AddEmptyLineBefore', [ 64, 68, 69, 73, 86, 87, 90, 91, 94 ] );
		$this->fileHasErrors( $phpcsFile, 'RemoveEmptyLineBefore', [ 66, 71, 78 ] );
	}
}
