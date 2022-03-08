<?php

class GoodExample {

	public function example( $args ) {

		$this->get_value();

		// Switch comment.
		switch ( $args ) {
			case 'a':
				example( $args );
				break;

			// Case comment.
			case 'b':
			case 'c':
				$value = 2;
				break;

			case 'd':
				break;

			// Default comment.
			default:
				$value = 3;
		}
	}

	public function example_2( $args ) {

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

		return $value;
	}

	public function good_example_3( $tokens, $nextLine ) {
		for ( $i = 0; $i < 10; $i ++ ) {
			if ( $tokens[ $i ]['line'] > $nextLine ) {
				break;
			}
		}
	}
}

class BadExample {

	public function example( $args ) {

		$this->get_value();
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

	public function example_2( $args ) {

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
		return $value;
	}
}
