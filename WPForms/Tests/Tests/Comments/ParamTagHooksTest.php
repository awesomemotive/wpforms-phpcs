<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\ParamTagHooksSniff;

/**
 * Class ParamTagHooksTest.
 *
 * @since 1.0.0
 */
class ParamTagHooksTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new ParamTagHooksSniff() );

		$this->fileHasErrors( $phpcsFile, 'InvalidAlign', [ 49, 50, 51, 52, 53 ] );
		$this->fileHasErrors( $phpcsFile, 'InvalidParamTagsQuantity', [ 75 ] );
		$this->fileHasErrors( $phpcsFile, 'MissParamInfo', [ 103, 104, 105, 106, 107, 108, 109 ] );
		$this->fileHasErrors( $phpcsFile, 'AddStopSymbol', [ 133, 134, 138 ] );
		$this->fileHasErrors( $phpcsFile, 'ExtraSpacesAfterParamTag', [ 160, 161, 162, 163, 164 ] );
	}
}
