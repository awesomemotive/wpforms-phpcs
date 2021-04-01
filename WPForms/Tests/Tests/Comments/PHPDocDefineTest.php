<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\PHPDocDefineSniff;

/**
 * Class PHPDocDefineTest.
 *
 * @since 1.0.0
 */
class PHPDocDefineTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new PHPDocDefineSniff() );

		$this->fileHasErrors( $phpcsFile, 'MissPHPDoc', [ 10 ] );
		$this->fileHasErrors( $phpcsFile, 'MissShortDescription', [ 12 ] );
		$this->fileHasErrors( $phpcsFile, 'AddStopSymbol', [ 18 ] );
	}
}
