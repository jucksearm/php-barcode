<?php

namespace jucksearm\barcode;

use jucksearm\barcode\lib\QRcodeFactory;

class QRcode
{
	public static function factory() {
		return new QRcodeFactory();
	}

	public static function html(
		$code,
		$emblem = null,
		$level = null,
		$size = null,
		$margin = null,
		$color = null,
		$skipOutput = false
	) {
		$qrcodeFactory = self::factory()
		  ->setCode($code)
		  ->setEmblem($emblem)
		  ->setLevel($level)
		  ->setSize($size)
		  ->setMargin($margin)
		  ->setColor($color)
		  ->setSkipOutput($skipOutput)
		  ->renderHTML();
	}

	public static function png(
		$code,
		$emblem = null,
		$file = null,
		$level = null,
		$size = null,
		$margin = null,
		$color = null,
		$skipOutput = false
	) {
		$qrcodeFactory = self::factory()
		  ->setCode($code)
		  ->setEmblem($emblem)
		  ->setFile($file)
		  ->setLevel($level)
		  ->setSize($size)
		  ->setMargin($margin)
		  ->setColor($color)
		  ->setSkipOutput($skipOutput)
		  ->renderPNG();
	}

	public static function svg(
		$code,
		$emblem = null,
		$file = null,
		$level = null,
		$size = null,
		$margin = null,
		$color = null,
		$skipOutput = false
	) {
		$qrcodeFactory = self::factory()
		  ->setCode($code)
		  ->setEmblem($emblem)
		  ->setFile($file)
		  ->setLevel($level)
		  ->setSize($size)
		  ->setMargin($margin)
		  ->setColor($color)
		  ->setSkipOutput($skipOutput)
		  ->renderSVG();
	}
}