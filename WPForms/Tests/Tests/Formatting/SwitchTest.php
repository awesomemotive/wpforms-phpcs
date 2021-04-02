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

		$this->fileHasErrors( $phpcsFile, 'AddEmptyLineBefore', [ 59, 63, 68, 81, 85, 89 ] );
		$this->fileHasErrors( $phpcsFile, 'RemoveEmptyLineBefore', [ 61, 66, 70 ] );
	}
}
