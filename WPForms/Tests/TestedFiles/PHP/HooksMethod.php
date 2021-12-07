<?php

class A {

	public function hooks() {

		add_action( 'test', [ $this, 'callback' ] );
		add_filter( 'test', [ $this, 'callback' ] );
		remove_action( 'test', [ $this, 'callback' ] );
		remove_filter( 'test', [ $this, 'callback' ] );
	}

	public function callback() {
	}
}

class B {

	public static function hooks() {

		add_action( 'test', [ __CLASS__, 'callback' ] );
		add_filter( 'test', [ __CLASS__, 'callback' ] );
		remove_action( 'test', [ __CLASS__, 'callback' ] );
		remove_filter( 'test', [ __CLASS__, 'callback' ] );
	}

	public static function callback() {
	}
}

class C {

	public function init() {

		add_action( 'test', [ $this, 'callback' ] );
		add_filter( 'test', [ $this, 'callback' ] );
		remove_action( 'test', [ $this, 'callback' ] );
		remove_filter( 'test', [ $this, 'callback' ] );
	}

	public function callback() {
	}
}

class D {

	public static function init() {

		add_action( 'test', [ __CLASS__, 'callback' ] );
		add_filter( 'test', [ __CLASS__, 'callback' ] );
		remove_action( 'test', [ __CLASS__, 'callback' ] );
		remove_filter( 'test', [ __CLASS__, 'callback' ] );
	}

	public static function callback() {
	}
}
