<?php

namespace WPForms\Traits;

use PHP_CodeSniffer\Files\File;

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
	 * @param string $tagName      Tag name.
	 * @param int    $commentStart PHPDoc start position.
	 * @param array  $tokens       List of tokens.
	 *
	 * @return array
	 */
	protected function findTag( $tagName, $commentStart, $tokens ) {

		$commentTags = ! empty( $tokens[ $commentStart ]['comment_tags'] ) ? $tokens[ $commentStart ]['comment_tags'] : [];

		foreach ( $commentTags as $commentTag ) {
			if ( $tokens[ $commentTag ]['content'] === $tagName ) {
				$tag        = $tokens[ $commentTag ];
				$tag['tag'] = $commentTag;

				return $tag;
			}
		}

		return [];
	}

	/**
	 * Find tags in PHPDoc.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tagName      Tag name.
	 * @param int    $commentStart PHPDoc start position.
	 * @param array  $tokens       List of tokens.
	 *
	 * @return array
	 */
	protected function findTags( $tagName, $commentStart, $tokens ) {

		$commentTags = ! empty( $tokens[ $commentStart ]['comment_tags'] ) ? $tokens[ $commentStart ]['comment_tags'] : [];
		$tags        = [];

		foreach ( $commentTags as $commentTag ) {
			if ( $tokens[ $commentTag ]['content'] === $tagName ) {
				$tag        = $tokens[ $commentTag ];
				$tag['tag'] = $commentTag;

				$tags[] = $tag;
			}
		}

		return $tags;
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
	 * @param File  $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param array $tag       Tag information.
	 *
	 * @return bool
	 */
	protected function isLastTag( $phpcsFile, $tag ) {

		$tokens       = $phpcsFile->getTokens();
		$closeComment = $phpcsFile->findNext( T_DOC_COMMENT_CLOSE_TAG, $tag['tag'] );

		return $tag['line'] === $tokens[ $closeComment ]['line'] - 1;
	}
}
