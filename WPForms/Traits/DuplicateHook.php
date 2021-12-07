<?php

namespace WPForms\Traits;

/**
 * Trait Version.
 *
 * @since 1.0.0
 */
trait DuplicateHook {

	use CommentTag;

	/**
	 * Tag has version.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $commentStart Comment start position.
	 * @param array $tokens       List of tokens.
	 *
	 * @return bool
	 */
	protected function isDuplicateHook( $commentStart, $tokens ) {

		$see = $this->findTag( '@see', $commentStart, $tokens );

		if ( ! $see ) {
			return false;
		}

		if ( $tokens[ $see['tag'] + 2 ]['code'] !== T_DOC_COMMENT_STRING ) {
			return false;
		}

		if ( 0 === strpos( $tokens[ $see['tag'] + 2 ]['content'], 'This filter is documented in ' ) ) {
			return true;
		}

		return 0 === strpos( $tokens[ $see['tag'] + 2 ]['content'], 'This action is documented in ' );
	}
}
