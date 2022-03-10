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

		$this->fileHasErrors( $phpcsFile, 'AddEmptyLineBefore', [ 91, 97, 117, 122, 125 ] );
		$this->fileHasErrors( $phpcsFile, 'RemoveEmptyLineBefore', [ 93, 96, 99, 102, 107, 116, 121 ] );
	}
}
