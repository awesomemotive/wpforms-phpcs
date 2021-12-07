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
	 * Test process without options.
	 *
	 * @since               1.0.0
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new ValidateDomainSniff() );

		$this->fileHasErrors( $phpcsFile, 'InvalidDomain', [ 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30 ] );
	}

	/**
	 * Test process with multi-domains.
	 *
	 * @since               1.0.0
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testProcessWithMultiDomains() {

		$phpcsFile = $this->process( new ValidateDomainSniff(), 'MultiDomains' );

		$this->fileHasErrors( $phpcsFile, 'InvalidDomain', [ 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 20, 21, 22, 23, 24, 25, 26 ] );
	}

	/**
	 * Test process with rewrites.
	 *
	 * @since               1.0.0
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testProcessWithRewrittenPaths() {

		$phpcsFile = $this->process( new ValidateDomainSniff(), 'ValidateDomainWithRewrites' );

		$this->fileHasErrors( $phpcsFile, 'InvalidDomain', [ 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 19, 20, 27, 28, 29, 30 ] );
	}
}
