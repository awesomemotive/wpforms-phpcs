<?php

function example( $args ) {

	switch ( $args ) {
		case 'a':
			example( $args );
			break;

		case 'b':
		case 'c':
			$value = 2;

			break;

		default:
			$value = 3;
	}
}

function example2( $args ) {

	switch ( $args ) {
		case 'a':
			$value = 1;

			break;

		case 'b':
		case 'c':
			$value = 2;

			break;

		default:
			$value = 3;
	}

	return $value;
}


function example( $args ) {

	$value = 0;
	switch ( $args ) {
		case 'a':
			example( $args );

			break;
		case 'b':

		case 'c':
			$value = 2;

			break;
		case 'd':
		case 'e':
			$value = 3;
			break;
		default:
			$value = 4;
	}
	return $value;
}
