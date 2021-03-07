<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\NoSpacesAfterReturnTagSniff;

/**
 * Class NoSpacesAfterReturnTagTest.
 *
 * @since 1.0.0
 */
class NoSpacesAfterReturnTagTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function test_process() {

		$phpcsFile = $this->process( new NoSpacesAfterReturnTagSniff() );

		$this->fileHasErrors( $phpcsFile, 'OnlyOneSpaceCanBeUsed', [ 35 ] );
	}
}
