<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\ShortDescriptionHooksSniff;

/**
 * Class ShortDescriptionHooksTest.
 *
 * @since 1.0.0
 */
class ShortDescriptionHooksTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new ShortDescriptionHooksSniff() );

		$this->fileHasErrors( $phpcsFile, 'MissShortDescription', [ 70, 86 ] );
		$this->fileHasErrors( $phpcsFile, 'AddStopSymbol', [ 109 ] );
	}
}
