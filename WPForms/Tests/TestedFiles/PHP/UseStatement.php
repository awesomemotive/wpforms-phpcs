<?php

namespace Test;

use WPForms\Traits\Version;
use Some\Name\Space\Example;
use Some\Name\Space\Example2;
use Some\Name\Space\Type;
use Some\Name\Space\Type2;
use Some\Name\Space\Type3;
use Some\Name\Space\Type4;
use Some\Name\Space\Type5;
use Unused\Name\Space\Example3;
use Unused\Name\Space\Example4;

use function some\name\space\func2;
use function unused\name\space\func3;

func( $a, $b, $c );
func2( $a, $b, $c );
new DateTime();
new Example( $a, $b );
Example2::class;


\func( $a, $b, $c );
\some\name\space\func2( $a, $b, $c );
new \DateTime( $a, $b );
new \Some\Name\Space\Example( $a, $b );
\Some\Name\Space\Example2::class;

class A {
	use Version;

	/**
	 * @var Type
	 */
	private $type;

	const TYPE = Type2::class;

	/**
	 * @param Type3 $type Type description.
	 */
	public function test( $type ) {}

	/**
	 * @return Type4
	 * @throws Type5 Type5.
	 */
	public function test2() {}
}
