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
		$this->fileHasErrors( $phpcsFile, 'InvalidParamTagsQuantity', [] );
		$this->fileHasErrors( $phpcsFile, 'MissParamInfo', [ 111, 112, 113, 114, 115, 116, 117 ] );
		$this->fileHasErrors( $phpcsFile, 'AddStopSymbol', [ 141, 142, 146 ] );
		$this->fileHasErrors( $phpcsFile, 'ExtraSpacesAfterParamTag', [ 168, 169, 170, 171, 172 ] );
	}
}
