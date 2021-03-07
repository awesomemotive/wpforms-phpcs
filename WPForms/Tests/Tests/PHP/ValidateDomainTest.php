<?php

namespace WPForms\Tests\PHP;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\PHP\ValidateDomainSniff;

/**
 * Class ValidateDomainTest.
 *
 * @since 1.0.0
 */
class ValidateDomainTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function test_process() {

		$phpcsFile = $this->process( new ValidateDomainSniff() );

		$this->fileHasErrors( $phpcsFile, 'InvalidDomain', [] );
	}
}
