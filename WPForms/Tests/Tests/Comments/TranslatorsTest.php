<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\TranslatorsSniff;

/**
 * Class TranslatorsTest.
 *
 * @since 1.0.0
 */
class TranslatorsTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new TranslatorsSniff() );

		$this->fileHasErrors( $phpcsFile, 'MissColon', [ 5 ] );
		$this->fileHasErrors( $phpcsFile, 'MissShortDescription', [ 6 ] );
		$this->fileHasErrors( $phpcsFile, 'InvalidCommentTag', [ 7 ] );
		$this->fileHasErrors( $phpcsFile, 'MissStopSymbol', [ 8 ] );
	}
}
