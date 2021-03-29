<?php

namespace WPForms\Tests\PHP;

use WPForms\Tests\TestCase;
use WPForms\Sniffs\PHP\ConditionsYodaDisableSniff;

/**
 * Class ConditionsYodaDisableTest.
 *
 * @since 1.0.0
 */
class ConditionsYodaDisableTest extends TestCase {

	/**
	 * Test process.
	 *
	 * @since 1.0.0
	 */
	public function testProcess() {

		$phpcsFile = $this->process( new ConditionsYodaDisableSniff() );

		$this->fileHasErrors( $phpcsFile, 'YodaRemove', [ 105, 106, 107, 108, 109, 110, 111, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 124, 125, 126, 127 ] );
	}
}
