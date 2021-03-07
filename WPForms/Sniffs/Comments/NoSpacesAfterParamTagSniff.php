<?php

namespace WPForms\Sniffs\Comments;

use WPForms\Sniffs\BaseSniff;
use WPForms\Traits\CommentTag;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class NoSpacesAfterParamTagSniff.
 *
 * @since 1.0.0
 */
class NoSpacesAfterParamTagSniff extends BaseSniff implements Sniff {

	use CommentTag;

	/**
	 * Tag name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $tag = '@param';

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_FUNCTION,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @since 1.0.0
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position in the PHP_CodeSniffer file's token stack where the token was found.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens        = $phpcsFile->getTokens();
		$comment_start = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $stackPtr );
		$tag           = $this->findTag( $this->tag, $comment_start, $tokens );

		if ( empty( $tag ) ) {
			return;
		}

		// More than 1 spaces.
		if ( $tokens[ $tag['tag'] + 1 ]['length'] > 1 ) {
			$phpcsFile->addError(
				sprintf(
					'Allows only 1 space after %s tag for %s() function.',
					$this->tag,
					$phpcsFile->getDeclarationName( $stackPtr )
				),
				$tag['tag'],
				'OnlyOneSpaceCanBeUsed'
			);
		}
	}
}
