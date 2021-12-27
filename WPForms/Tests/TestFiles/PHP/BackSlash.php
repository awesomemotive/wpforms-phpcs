<?php

namespace Test;

use Some\Name\Space\Example;
use Some\Name\Space\Example2;

use function some\name\space\func2;

func( $a, $b, $c );
func2( $a, $b, $c );
new DateTime();
new Example( $a, $b );
Example2::class;

/**
 * description that provide some example with code
 * example:
 * \DateTime or
 * \Some\Name\Space\Example( $a, $b ).
 */
function test( $type4 ) {}


\func( $a, $b, $c );
\some\name\space\func2( $a, $b, $c );
new \DateTime( $a, $b );
new \Some\Name\Space\Example( $a, $b );
\Some\Name\Space\Example2::class;


/**
 * @param \Some\Name\Space\Type4 $type4
 *
 * @return \Some\Name\Space\Type4
 */
function test2( $type4 ) {}

/**
 * @param \DateTime $dateTime Example.
 *
 * @return \DateTime
 */
function test3( $type4 ) {}
