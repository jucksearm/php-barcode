<?php

require dirname(__FILE__).'/../vendor/autoload.php';

use jucksearm\barcode\Barcode;

Barcode::svg('https://github.com/jucksearm/php-barcode', 'C128');