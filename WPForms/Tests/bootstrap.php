<?php
/**
 * Bootstrap file for phpunit tests.
 */

/**
 * Path to tests.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_TESTS_PATH', str_replace( '/', DIRECTORY_SEPARATOR, __DIR__ . '/' ) );

/**
 * Path to sniffs.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_SNIFFS_PATH', str_replace( '/', DIRECTORY_SEPARATOR, realpath( __DIR__ . '/../Sniffs' ) . '/' ) );

/**
 * Path to test files.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_TESTED_FILES_PATH', str_replace( '/', DIRECTORY_SEPARATOR, __DIR__ . '/TestedFiles/' ) );

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/squizlabs/php_codesniffer/tests/bootstrap.php';
