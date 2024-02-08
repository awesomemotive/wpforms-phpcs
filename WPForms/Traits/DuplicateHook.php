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

		$commentEnd = $tokens[ $commentStart ]['comment_closer'] ?? false;

		$see = $this->findTag( '@see', $commentStart, $tokens );

		if ( $see && $commentEnd && ( $see['tag'] < $commentEnd ) ) {
			$commentStringPtr = $see['tag'] + 2;
		} else {
			$commentStringPtr = $commentStart + 2;

			if ( $tokens[ $commentStart ]['line'] !== $tokens[ $commentEnd ]['line'] ) {
				return false;
			}
		}

		$content = $tokens[ $commentStringPtr ]['content'];

		if ( $tokens[ $commentStringPtr ]['code'] !== T_DOC_COMMENT_STRING ) {
			return false;
		}

		return (
			0 === strpos( $content, 'This action is documented in ' ) ||
			0 === strpos( $content, 'This filter is documented in ' )
		);
	}
}
