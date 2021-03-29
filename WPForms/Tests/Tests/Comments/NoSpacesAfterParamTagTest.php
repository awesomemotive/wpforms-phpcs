<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\NoSpacesAfterParamTagSniff;

/**
 * Class NoSpacesAfterReturnTagTest.
 *
 * @since 1.0.0
 */
class NoSpacesAfterParamTagTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new NoSpacesAfterParamTagSniff() );

		$this->fileHasErrors( $phpcsFile, 'OnlyOneSpaceCanBeUsed', [ 27 ] );
	}
}
