<?php

// language=CSS
$languageInjectionValid = 'body p {font-family: sans-serif}';

//  language=CSS
$languageInjectionInvalidTwoSpaces = 'body p {font-family: sans-serif}';

$languageInjectionValid = /** @lang HTML */ '<body></body>';

$langInjectionInvalidLong = /** @language HTML */ '<body></body>';
