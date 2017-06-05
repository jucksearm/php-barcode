<?php

namespace jucksearm\barcode;

use jucksearm\barcode\lib\BarcodeFactory;

class Barcode
{
	public static function factory() {
		return new BarcodeFactory();
	}

	public static function html(
		$code,
		$type,
		$scale = null,
		$height = null,
		$rotate = null,
		$color = null
	) {
		$barcodeFactory = self::factory()
		  ->setCode($code)
		  ->setType($type)
		  ->setScale($scale)
		  ->setHeight($height)
		  ->setRotate($rotate)
		  ->setColor($color)
		  ->renderHTML();
	}

	public static function png(
		$code,
		$type,
		$file = null,
		$scale = null,
		$height = null,
		$rotate = null,
		$color = null
	) {
		$barcodeFactory = self::factory()
		  ->setCode($code)
		  ->setType($type)
		  ->setFile($file)
		  ->setScale($scale)
		  ->setHeight($height)
		  ->setRotate($rotate)
		  ->setColor($color)
		  ->renderPNG();
	}

	public static function svg(
		$code,
		$type,
		$file = null,
		$scale = null,
		$height = null,
		$rotate = null,
		$color = null
	) {
		$barcodeFactory = self::factory()
		  ->setCode($code)
		  ->setType($type)
		  ->setFile($file)
		  ->setScale($scale)
		  ->setHeight($height)
		  ->setRotate($rotate)
		  ->setColor($color)
		  ->renderSVG();
	}
}