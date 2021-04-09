<?php

namespace WPForms\Tests\PHP;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\PHP\BackSlashSniff;

/**
 * Class BackSlashTest.
 *
 * @since 1.0.0
 */
class BackSlashTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new BackSlashSniff() );

		$this->fileHasErrors( $phpcsFile, 'RemoveBackslash', [ 25, 27, 40, 42 ] );
		$this->fileHasErrors( $phpcsFile, 'UseShortSyntax', [ 26, 28, 29, 33, 35 ] );
	}
}
