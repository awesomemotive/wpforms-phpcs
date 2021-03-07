<?php

define( 'WPFORMS_SNIFFS_PATH', realpath( __DIR__ . '/../Sniffs' ) . '/' );
define( 'WPFORMS_TESTED_FILES_PATH', __DIR__ . '/TestedFiles/' );

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/squizlabs/php_codesniffer/tests/bootstrap.php';
