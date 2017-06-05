[![Packagist Downloads](https://img.shields.io/packagist/dt/jucksearm/php-barcode.svg)](https://packagist.org/packages/jucksearm/php-barcode) [![Stable version](https://img.shields.io/packagist/v/jucksearm/php-barcode.svg)](https://packagist.org/packages/jucksearm/php-barcode) [![License](https://img.shields.io/packagist/l/jucksearm/php-barcode.svg)](https://packagist.org/packages/jucksearm/php-barcode)

This is a barcode generation package inspired by [Nicola Asuni](https://github.com/tecnickcom/TCPDF). Actually I use that package's underline classes for generating barcode. This package is just a wrapper of that package and adds compatibility with PHP >= 5.4

I used the following classes of that package.

- lib/Barcode1D.php ([tcpdf_barcodes_1d.php](https://github.com/tecnickcom/TCPDF/blob/master/tcpdf_barcodes_1d.php))
- lib/Barcode2D.php ([tcpdf_barcodes_2d.php](https://github.com/tecnickcom/TCPDF/blob/master/tcpdf_barcodes_2d.php))
- lib/QRcode.php ([include/barcodes/qrcode.php](https://github.com/tecnickcom/TCPDF/blob/master/include/barcodes/qrcode.php))
- lib/Datamatrix.php ([include/barcodes/datamatrix.php](https://github.com/tecnickcom/TCPDF/blob/master/include/barcodes/datamatrix.php))
- lib/PDF417.php ([include/barcodes/pdf417.php](https://github.com/tecnickcom/TCPDF/blob/master/include/barcodes/pdf417.php))

[Read More on TCPDF website](http://www.tcpdf.org)

## Support
Barcode generator like QRCode, PDF417, Datamatrix, C39, C39+, C39E, C39E+, C93, S25, S25+, I25, I25+, C128, C128A,C128B, C128C, 2-Digits UPC-Based Extention, 5-Digits UPC-Based Extention, EAN 8, EAN 13, UPC-A, UPC-E, MSI (Variation of Plessey code) generator in HTML, PNG and SVG.

## This package is compatible with PHP >= 5.4

This package require [php-gd](http://php.net/manual/en/book.image.php) extension. So, make sure it is installed on your machine.

## Installation

Begin by installing this package through Composer. Just run following command to terminal:

```
composer require jucksearm/php-barcode
```

You can also edit your project's `composer.json` file to require `jucksearm/php-barcode`.

```
"require": {
    ...
    "jucksearm/php-barcode": "^1.0"
}
```

Next, update Composer from the terminal:

```
composer update
```
## How to Use Basic
```php
use jucksearm\barcode\Barcode;

Barcode::html('https://github.com/jucksearm/php-barcode', 'C128');
```
## How to Use Advance
```php
use jucksearm\barcode\Barcode;

Barcode::factory()
  ->setCode('https://github.com/jucksearm/php-barcode')
  ->setType('C128')
  ->setScale(null)
  ->setHeight(null)
  ->setRotate(null)
  ->setColor(null)
  ->renderHTML();
```
[See More Examples](https://github.com/jucksearm/php-barcode/examples)

## Barcode Option

```php
Barcode::html($code, $type, $scale = null, $height = null, $rotate = null, $color = null)

Barcode::png($code, $type, $file= null, $scale = null, $height = null, $rotate = null, $color = null)

Barcode::svg($code, $type, $file= null, $scale = null, $height = null, $rotate = null, $color = null)
```

```php
$type    C39, C39+, C39E, C39E+, C93, S25, S25+, I25, I25+, C128, C128A, C128B, C128C, EAN2, EAN5, EAN8, EAN13, UPCA, UPCE, MSI, MSI+, POSTNET, PLANET, RMS4CC, KIX, IMB, CODABAR, CODE11, PHARMA, PHARMA2T
$file    Barcode save filename [default: `null`]
$scale   Barcode unit size in `px` units [default: `1`]
$height  Barcode height in `px` units [default: `30`]
$rotate  Support 0, 90 in `degrees` units [default: `0`]
$color   Support in `hexadecimal` color units [default: `000`]
```

## QRcode Option

```php
QRcode::html($code, $emblem = null, $level = null, $size = null, $margin = null, $color = null)

QRcode::png($code, $emblem = null, $file = null, $level = null, $size = null, $margin = null, $color = null)

QRcode::svg($code, $emblem = null, $file = null, $level = null, $size = null, $margin = null, $color = null)
```

```php
$emblem  Insert mask Logo [default: `null`]
$file    QRcode save filename [default: `null`]
$level   QRcode level L,M,Q,H [default: `L`]
$size    QRcode width and height size in `px` units [default: `100`]
$margin  QRcode empty space in `percentage` units [default: `1`]
$color   Support in `hexadecimal` color units [default: `000`]
```

## Datamatrix Option

```php
Datamatrix::html($code, $size = null, $margin = null, $color = null)

Datamatrix::png($code, $file = null, $size = null, $margin = null, $color = null)

Datamatrix::svg($code, $file = null, $size = null, $margin = null, $color = null)
```

```php
$file    Datamatrix save filename [default: `null`]
$size    Datamatrix width and height size in `px` units [default: `100`]
$margin  Datamatrix empty space in `percentage` units [default: `1`]
$color   Support in `hexadecimal` color units [default: `000`]
```

## PDF417 Option

```php
PDF417::html($code, $size = null, $margin = null, $color = null)

PDF417::png($code, $file = null, $size = null, $margin = null, $color = null)

PDF417::svg($code, $file = null, $size = null, $margin = null, $color = null)
```

```php
$file    PDF417 save filename [default: `null`]
$size    PDF417 width and height size in `px` units [default: `100`]
$margin  PDF417 empty space in `percentage` units [default: `1`]
$color   Support in `hexadecimal` color units [default: `000`]
```

## License

This package is published under `GNU LGPLv3` license and copyright to [Jucksearm Boonmor](https://github.com/jucksearm/php-barcode). Original Barcode generation classes were written by [Nicola Asuni](https://github.com/tecnickcom/barcode). The license agreement is on project's root.

License: GNU LGPLv3
* **Original Package**      Nicola Asuni https://github.com/tecnickcom/TCPDF
* **Link**                  http://www.tcpdf.org
* **Package Copyright**     Jucksearm Boonmor <jucksearm.bkk@gmail.com>