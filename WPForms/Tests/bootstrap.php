<?php
/**
 * Bootstrap file for phpunit tests.
 */

/**
 * Path to project root folder.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_ROOT_PATH', str_replace( '/', DIRECTORY_SEPARATOR, dirname( __DIR__, 2 ) . '/' ) );

/**
 * Path to tests.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_TESTS_PATH', str_replace( '/', DIRECTORY_SEPARATOR, __DIR__ . '/' ) );

/**
 * Path to the sniffs.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_SNIFFS_PATH', str_replace( '/', DIRECTORY_SEPARATOR, dirname( __DIR__ ) . '/Sniffs/' ) );

/**
 * Path to test files.
 *
 * @since 1.0.0
 */
define( 'WPFORMS_TEST_FILES_PATH', str_replace( '/', DIRECTORY_SEPARATOR, __DIR__ . '/TestFiles/' ) );

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/squizlabs/php_codesniffer/tests/bootstrap.php';
