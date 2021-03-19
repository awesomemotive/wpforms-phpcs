<?php

namespace WPForms\Traits;

/**
 * Trait Version.
 *
 * @since 1.0.0
 */
trait Version {

	/**
	 * Tag has version.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tag    Tag information.
	 * @param array $tokens List of tokens.
	 *
	 * @return bool
	 */
	protected function hasVersion( $tag, $tokens ) {

		$version = $tokens[ ( $tag['tag'] + 2 ) ]['content'];

		return ! empty( $version ) && $tokens[ ( $tag['tag'] + 2 ) ]['code'] === T_DOC_COMMENT_STRING;
	}

	/**
	 * Is valid version.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tag    Tag information.
	 * @param array $tokens List of tokens.
	 *
	 * @return bool
	 */
	protected function isValidVersion( $tag, $tokens ) {

		$version = $tokens[ ( $tag['tag'] + 2 ) ]['content'];

		return (bool) preg_match( '/^([\d.]+[\d]|{VERSION})$/', $version );
	}
}
