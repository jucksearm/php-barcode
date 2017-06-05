<?php

require dirname(__FILE__).'/../vendor/autoload.php';

use jucksearm\barcode\QRcode;

QRcode::svg('https://github.com/jucksearm/php-barcode');