<?php

namespace WPForms\Tests\Comments;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\Comments\PHPDocHooksSniff;

/**
 * Class PHPDocHooksTest.
 *
 * @since 1.0.0
 */
class PHPDocHooksTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new PHPDocHooksSniff() );

		$this->fileHasErrors( $phpcsFile, 'RequiredHookDocumentation', [ 69, 82 ] );
	}
}
