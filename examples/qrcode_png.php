<?php

require dirname(__FILE__).'/../vendor/autoload.php';

use jucksearm\barcode\QRcode;

QRcode::png('https://github.com/jucksearm/php-barcode');