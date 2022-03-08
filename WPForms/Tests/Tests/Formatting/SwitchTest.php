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

		$this->fileHasErrors( $phpcsFile, 'AddEmptyLineBefore', [ 63, 69, 89, 94, 97 ] );
		$this->fileHasErrors( $phpcsFile, 'RemoveEmptyLineBefore', [ 65, 68, 71, 74, 79, 88, 93 ] );
	}
}
