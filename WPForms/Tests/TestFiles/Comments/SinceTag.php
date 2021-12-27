<?php
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound

/**
 * Since function.
 *
 * @since 1.0.0
 */
function since_function() {
}

/**
 * Class Since.
 *
 * @since 1.0.0
 */
class Since {

	/**
	 * Since const.
	 *
	 * @since 1.0.0
	 */
	const SINCE_CONST = 'const';

	/**
	 * Since method.
	 *
	 * @since 1.0.0
	 */
	public function since_method() {
	}

	/**
	 * Since static method.
	 *
	 * @since 1.0.0
	 */
	public static function since_static_method() {
	}

	/**
	 * Since method.
	 *
	 * @since 1.0.0
	 * @since 1.0.0
	 */
	public function since_method_2() {
	}

	/**
	 * AJAX to add a provider from the settings integrations tab.
	 *
	 * @since 1.0.0
	 *
	 * phpcs:ignore Squiz.Commenting.FunctionCommentThrowTag.Missing
	 */
	public function since_method_3() {
	}
}

/**
 * Since function.
 */
function invalid_since_function() {
}


/**
 * Class InvalidSince.
 */
class InvalidSince {

	/**
	 * Since const.
	 */
	const SINCE_CONST = 'const';

	/**
	 * Since method.
	 */
	public function since_method() {
	}

	/**
	 * AJAX to add a provider from the settings integrations tab.
	 *
	 * @since 1.0.0
	 * @deprecated 1.1.0
	 */
	public function since_method_2() {
	}

	/**
	 * AJAX to add a provider from the settings integrations tab.
	 *
	 * @since {VER}
	 */
	public function since_method_3() {
	}

	/**
	 * AJAX to add a provider from the settings integrations tab.
	 *
	 * @since 1.0.0
	 *
	 * @deprecated 1.1.0
	 */
	public function since_method_4() {
	}

	/**
	 * Since static method.
	 */
	public static function since_static_method() {
	}

	/**
	 * Since static method.
	 * @since
	 */
	const SINCE_EMPTY_VERSION_CONST = 'const';

	/**
	 * Since static method.
	 * @since 1.0.0
	 */
	const SINCE_EMPTY_LINE_CONST = 'const';

	/**
	 * Since static method.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const SINCE_EMPTY_LINE_CONST_2 = 'const';
}

// phpcs:enable Generic.Files.OneObjectStructurePerFile.MultipleFound
