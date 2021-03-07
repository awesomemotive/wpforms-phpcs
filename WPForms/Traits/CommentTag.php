<?php

namespace WPForms\Traits;

/**
 * Trait CommentTag.
 *
 * @since 1.0.0
 */
trait CommentTag {

	/**
	 * Find tag in PHPDoc.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag           Tag name.
	 * @param int    $comment_start PHPDoc start position.
	 * @param array  $tokens        List of tokens.
	 *
	 * @return array|mixed
	 */
	protected function findTag( $tag, $comment_start, $tokens ) {

		$comment_tags = ! empty( $tokens[ $comment_start ]['comment_tags'] ) ? $tokens[ $comment_start ]['comment_tags'] : [];

		foreach ( $comment_tags as $comment_tag ) {
			if ( $tokens[ $comment_tag ]['content'] === $tag ) {
				$since        = $tokens[ $comment_tag ];
				$since['tag'] = $comment_tag;

				return $since;
			}
		}

		return [];
	}

	/**
	 * Has Empty Line after tag in this comment.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tag    Tag information.
	 * @param array $tokens List of tokens.
	 *
	 * @return bool
	 */
	protected function hasEmptyLineAfterInComment( $tag, $tokens ) {

		return $tokens[ $tag['tag'] + 5 ]['code'] === T_DOC_COMMENT_STAR &&
		       $tokens[ $tag['tag'] + 6 ]['code'] === T_DOC_COMMENT_WHITESPACE &&
		       $tokens[ $tag['tag'] + 7 ]['code'] === T_DOC_COMMENT_WHITESPACE;
	}

	/**
	 * Is last tag.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tag    Tag information.
	 * @param array $tokens List of tokens.
	 *
	 * @return bool
	 */
	protected function isLastTag( $tag, $tokens ) {

		return $tokens[ $tag['tag'] + 5 ]['type'] === 'T_DOC_COMMENT_CLOSE_TAG';
	}
}
