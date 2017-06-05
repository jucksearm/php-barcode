<?php

namespace jucksearm\barcode;

use jucksearm\barcode\lib\DatamatrixFactory;

class Datamatrix
{
	public static function factory() {
		return new DatamatrixFactory();
	}

	public static function html(
		$code,
		$size = null,
		$margin = null,
		$color = null
	) {
		$datamatrixFactory = self::factory()
		  ->setCode($code)
		  ->setSize($size)
		  ->setMargin($margin)
		  ->setColor($color)
		  ->renderHTML();
	}

	public static function png(
		$code,
		$file = null,
		$size = null,
		$margin = null,
		$color = null
	) {
		$datamatrixFactory = self::factory()
		  ->setCode($code)
		  ->setFile($file)
		  ->setSize($size)
		  ->setMargin($margin)
		  ->setColor($color)
		  ->renderPNG();
	}

	public static function svg(
		$code,
		$file = null,
		$size = null,
		$margin = null,
		$color = null
	) {
		$datamatrixFactory = self::factory()
		  ->setCode($code)
		  ->setFile($file)
		  ->setSize($size)
		  ->setMargin($margin)
		  ->setColor($color)
		  ->renderSVG();
	}
}