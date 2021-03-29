<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\DescriptionStopSymbolPropertySniff;

/**
 * Class DescriptionStopSymbolTest.
 *
 * @since 1.0.0
 */
class DescriptionStopSymbolPropertyTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new DescriptionStopSymbolPropertySniff() );

		$this->fileHasErrors( $phpcsFile, 'MissDescription', [ 73 ] );
		$this->fileHasErrors( $phpcsFile, 'AddStopSymbol', [ 82, 91, 102 ] );
	}
}
