<?php

namespace Test;

use Some\Name\Space\Example;
use Some\Name\Space\Example2;
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
