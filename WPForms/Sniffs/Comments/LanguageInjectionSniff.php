<?php

namespace WPForms\Sniffs\Comments;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use WPForms\Sniffs\BaseSniff;

/**
 * Class LanguageInjectionSniff.
 *
 * @since 1.0.0
 */
class LanguageInjectionSniff extends BaseSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function register() {

		return [
			T_COMMENT,
			T_DOC_COMMENT_OPEN_TAG,
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

		$tokens = $phpcsFile->getTokens();

		// Allow `// language=` language injection comment.
		if ( $tokens[ $stackPtr ]['type'] === 'T_COMMENT' && strpos( $tokens[ $stackPtr ]['content'], '// language=' ) === 0 ) {
			// Block 'Inline comments must end in full-stops, exclamation marks, or question marks' error.
			$phpcsFile->tokenizer->ignoredLines[ $tokens[ $stackPtr ]['line'] ] = [ 'Squiz.Commenting.InlineComment.InvalidEndChar' => true ];

			return;
		}

		if (
			isset( $tokens[ $stackPtr ]['comment_closer'] ) === false
			|| (
				$tokens[ $tokens[ $stackPtr ]['comment_closer'] ]['content'] === ''
				&& $tokens[ $stackPtr ]['comment_closer'] === ( $phpcsFile->numTokens - 1 )
			)
		) {
			return; // Don't process an unfinished comment during live coding.
		}

		$commentEnd = $tokens[ $stackPtr ]['comment_closer'];

		$empty = [
			T_DOC_COMMENT_WHITESPACE,
			T_DOC_COMMENT_STAR,
		];

		$short = $phpcsFile->findNext( $empty, ( $stackPtr + 1 ), $commentEnd, true );

		// Allow `@lang` language injection comment.
		if ( $short && $tokens[ $short ]['code'] === T_DOC_COMMENT_TAG && $tokens[ $short ]['content'] === '@lang' ) {
			// Block 'Missing short description in doc comment' error.
			$phpcsFile->tokenizer->ignoredLines[ $tokens[ $short ]['line'] ] = [ 'Generic.Commenting.DocComment.MissingShort' => true ];
		}
	}
}
