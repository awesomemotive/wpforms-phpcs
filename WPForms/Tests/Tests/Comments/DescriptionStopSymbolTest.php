<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\DescriptionStopSymbolSniff;

/**
 * Class DescriptionStopSymbolTest.
 *
 * @since 1.0.0
 */
class DescriptionStopSymbolTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function test_process() {

		$phpcsFile = $this->process( new DescriptionStopSymbolSniff() );

		$this->fileHasErrors( $phpcsFile, 'MissDescription', [ 68 ] );
		$this->fileHasErrors( $phpcsFile, 'AddStopSymbol', [ 62, 77, 86, 95, 103, 113 ] );
	}
}
