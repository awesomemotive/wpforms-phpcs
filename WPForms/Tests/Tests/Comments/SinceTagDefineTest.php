<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\SinceTagDefineSniff;

/**
 * Class SinceTagDefineTest.
 *
 * @since 1.0.0
 */
class SinceTagDefineTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new SinceTagDefineSniff() );

		$this->fileHasErrors( $phpcsFile, 'MissingSinceTag', [ 15 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingSinceVersion', [ 20 ] );
		$this->fileHasErrors( $phpcsFile, 'InvalidSinceVersion', [ 27 ] );
		$this->fileHasErrors( $phpcsFile, 'EmptyLineAfterSince', [ 34 ] );
	}
}
