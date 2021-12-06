<?php

define( 'WPFORMS_TESTS_PATH', str_replace( '/', DIRECTORY_SEPARATOR, __DIR__ . '/' ) );
define( 'WPFORMS_SNIFFS_PATH', str_replace( '/', DIRECTORY_SEPARATOR, realpath( __DIR__ . '/../Sniffs' ) . '/' ) );
define( 'WPFORMS_TESTED_FILES_PATH', str_replace( '/', DIRECTORY_SEPARATOR, __DIR__ . '/TestedFiles/' ) );

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/squizlabs/php_codesniffer/tests/bootstrap.php';
