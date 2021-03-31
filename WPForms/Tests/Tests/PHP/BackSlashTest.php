<?php

namespace WPForms\Tests\PHP;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\PHP\BackslashSniff;

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

		$this->fileHasErrors( $phpcsFile, 'RemoveBackslash', [ 17, 19 ] );
		$this->fileHasErrors( $phpcsFile, 'UseShortSyntax', [ 18, 20, 21 ] );
	}
}
