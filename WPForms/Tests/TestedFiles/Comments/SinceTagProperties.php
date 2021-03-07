<?php

/**
 * Class Since.
 *
 * @since 1.0.0
 */
class Since {

	/**
	 * Property with since.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed
	 */
	public $property_with_since;

	/**
	 * Static property with since.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed
	 */
	public static $static_property_with_since;

	/**
	 * Property with since.
	 *
	 * @since      1.0.0
	 * @deprecated 1.0.1
	 *
	 * @var mixed
	 */
	public $property_with_since_and_deprecate;

	/**
	 * Static property with since.
	 *
	 * @since      1.0.0
	 * @deprecated 1.0.1
	 *
	 * @var mixed
	 */
	public static $static_property_with_since_and_deprecate;

	/**
	 * Property with double since.
	 *
	 * @since      1.0.0
	 * @since      1.0.1
	 *
	 * @var mixed
	 */
	public $property_with_double_since;

	/**
	 * Property with valid version.
	 *
	 * @since 1.0.0.4
	 *
	 * @var mixed
	 */
	public $property_valid_version;

	/**
	 * Property with valid version.
	 *
	 * @since {VERSION}
	 *
	 * @var mixed
	 */
	public $property_valid_version_2;

	/**
	 * Invalid
	 */

	/**
	 * Property with since.
	 *
	 * @since
	 *
	 * @var mixed
	 */
	public $property_with_invalid_version_since;

	/**
	 * Static property with since.
	 *
	 * @since
	 *
	 * @var mixed
	 */
	public static $static_property_with_invalid_version_since;

	/**
	 * Property with since.
	 *
	 * @since      1.0.0
	 *
	 * @deprecated 1.0.1
	 *
	 * @var mixed
	 */
	public $property_with_invalid_since_and_deprecate;

	/**
	 * Static property with since.
	 *
	 * @since      1.0.0
	 *
	 * @deprecated 1.0.1
	 *
	 * @var mixed
	 */
	public static $static_property_with_invalid_since_and_deprecate;

	/**
	 * Property without since.
	 *
	 * @var mixed
	 */
	public $property_without_since;

	/**
	 * Static property without since.
	 *
	 * @var mixed
	 */
	public static $static_property_without_since;

	/**
	 * Property with invalid version.
	 *
	 * @since 1.0.0.
	 *
	 * @var mixed
	 */
	public $property_invalid_version;

	/**
	 * Property with invalid version.
	 *
	 * @since asdas
	 *
	 * @var mixed
	 */
	public $property_invalid_version_2;

	/**
	 * Property with invalid version.
	 *
	 * @since {VERS}
	 *
	 * @var mixed
	 */
	public $property_invalid_version_3;

	/**
	 * Property with invalid version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $property_invalid_version_4;
}
