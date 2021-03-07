<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\DeprecatedTagSniff;

/**
 * Class DescriptionStopSymbolTest.
 *
 * @since 1.0.0
 */
class DeprecatedTagTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function test_process() {

		$phpcsFile = $this->process( new DeprecatedTagSniff() );

		$this->fileHasErrors( $phpcsFile, 'InvalidDeprecatedVersion', [ 67 ] );
		$this->fileHasErrors( $phpcsFile, 'MissingEmptyLineAfterDeprecated', [ 44 ] );
	}
}
