<?php

namespace WPForms\Sniffs\Comments;

/**
 * Class NoSpacesAfterReturnTagSniff.
 *
 * @since 1.0.0
 */
class NoSpacesAfterReturnTagSniff extends NoSpacesAfterParamTagSniff {

	/**
	 * Tag name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $tag = '@return';
}
