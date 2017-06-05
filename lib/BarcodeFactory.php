<?php

namespace jucksearm\barcode\lib;

use jucksearm\barcode\lib\Barcode1D;

class BarcodeFactory
{
	private $_attributes;
	private $_scalePx = 1;
	private $_heightPx = 1;

	public function __construct()
	{
		$this->_attributes = [
			'code' => null,
			'type' => null,
			'file' => null,
			'scale'  => 1,
			'height' => 30,
			'rotate' => 0,
			'color'  => '000',
		];
	}

	public function __set($name, $value)
	{
		$setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            // set property
            $this->$setter($value);

            return;
         }
	}
	
	public function __get($name)
	{
		$getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            // read property
            return $this->$getter();
        }
	}

	public function setAttribute($name, $value)
	{
		if ($value === null) return;

		$this->_attributes[$name] = $value;
	}

	public function getAttribute($name)
	{
		return $this->_attributes[$name];
	}

	public function setAttributes($attrs = [])
	{
		array_merge($this->_attributes, $attrs);
	}

	public function getAttributes()
	{
		return $this->_attributes;
	}

	public function setCode($value)
	{
		$this->setAttribute('code', $value);
		return $this;
	}

	public function getCode()
	{
		return $this->getAttribute('code');
	}

	public function setType($value)
	{
		$this->setAttribute('type', $value);
		return $this;
	}

	public function getType()
	{
		return $this->getAttribute('type');
	}

	public function setFile($value)
	{
		$this->setAttribute('file', $value);
		return $this;
	}

	public function getFile()
	{
		return $this->getAttribute('file');
	}

	public function setScale($value)
	{
		$this->setAttribute('scale', $value);
		return $this;
	}

	public function getScale()
	{
		return $this->getAttribute('scale') * $this->_scalePx;
	}

	public function setHeight($value)
	{
		$this->setAttribute('height', $value);
		return $this;
	}

	public function getHeight()
	{
		return $this->getAttribute('height') * $this->_heightPx;
	}

	public function setRotate($value)
	{
		$value = abs($value);
		if ($value != 0 && $value%90 != 0) return $this;

		$this->setAttribute('rotate', $value);
		return $this;
	}

	public function getRotate()
	{
		return $this->getAttribute('rotate');
	}

	public function setColor($value)
	{
		$this->setAttribute('color', $value);
		return $this;
	}

	public function getColor()
	{
		return $this->getAttribute('color');
	}

	public function getBarcode()
	{
		return new Barcode1D($this->code, $this->type);
	}

	public function getHexColor()
	{
		$color = $this->color;

		return '#'.$color;
	}

	public function getRgbColor()
	{
		$color = $this->color;

		if (strlen($color) > 3) {
			$r = hexdec(substr($color, 0, 2));
			$g = hexdec(substr($color, 2, 2));
			$b = hexdec(substr($color, 4, 2));
		} else {
			$r = hexdec(substr($color, 0, 1).substr($color, 0, 1));
			$g = hexdec(substr($color, 1, 1).substr($color, 1, 1));
			$b = hexdec(substr($color, 2, 1).substr($color, 2, 1));
		}

		return [$r, $g, $b];
	}

	public function getBarcodeHtmlData()
	{
		$bcd = $this->barcode->getBarcodeArray();
		$rotate = $this->rotate;
		$color = $this->hexColor;

		if ($rotate == 0 || $rotate%180 == 0) {
			$w = $this->scale;
			$h = $this->height;

			$barcodeData = '<div style="font-size:0;position:relative;width:'.($bcd['maxw'] * $w).'px;height:'.($h).'px;">'."\n";
			// print bars
			$x = 0;
			foreach ($bcd['bcode'] as $k => $v) {
				$bw = round(($v['w'] * $w), 3);
				$bh = round(($v['h'] * $h / $bcd['maxh']), 3);
				if ($v['t']) {
					$y = round(($v['p'] * $h / $bcd['maxh']), 3);
					// draw a vertical bar
					$barcodeData .= '<div style="background-color:'.$color.';width:'.$bw.'px;height:'.$bh.'px;position:absolute;left:'.$x.'px;top:'.$y.'px;">&nbsp;</div>'."\n";
				}
				$x += $bw;
			}
			$barcodeData .= '</div>'."\n";
		} else {
			$w = $this->height;
			$h = $this->scale;

			$barcodeData = '<div style="font-size:0;position:relative;width:'.($w).'px;height:'.($bcd['maxw'] * $h).'px;">'."\n";
			// print bars
			$y = 0;
			foreach ($bcd['bcode'] as $k => $v) {
				$bw = round(($v['h'] * $w / $bcd['maxh']), 3);
				$bh = round(($v['w'] * $h), 3);
				if ($v['t']) {
					$x = round(($v['p'] * $h / $bcd['maxh']), 3);
					// draw a vertical bar
					$barcodeData .= '<div style="background-color:'.$color.';width:'.$bw.'px;height:'.$bh.'px;position:absolute;left:'.$x.'px;top:'.$y.'px;">&nbsp;</div>'."\n";
				}
				$y += $bh;
			}
			$barcodeData .= '</div>'."\n";
		}

		return $barcodeData;
	}

	public function getBarcodePngData()
	{
		$bcd = $this->barcode->getBarcodeArray();
		$rotate = $this->rotate;
		$color = $this->rgbColor;

		if ($rotate == 0 || $rotate%180 == 0) {
			$w = $this->scale;
			$h = $this->height;

			if (function_exists('imagecreate')) {
				$png = imagecreate(($bcd['maxw'] * $w), $h);
				$bgcol = imagecolorallocate($png, 255, 255, 255);
				// imagecolortransparent($png, $bgcol);
				$fgcol = imagecolorallocate($png, $color[0], $color[1], $color[2]);
			} else {
				return false;
			}
			// print bars
			$x = 0;
			foreach ($bcd['bcode'] as $k => $v) {
				$bw = round(($v['w'] * $w), 3);
				$bh = round(($v['h'] * $h / $bcd['maxh']), 3);
				if ($v['t']) {
					$y = round(($v['p'] * $h / $bcd['maxh']), 3);
					// draw a vertical bar
					if ($imagick) {
						$bar->rectangle($x, $y, ($x + $bw - 1), ($y + $bh - 1));
					} else {
						imagefilledrectangle($png, $x, $y, ($x + $bw - 1), ($y + $bh - 1), $fgcol);
					}
				}
				$x += $bw;
			}
		} else {
			$w = $this->height;
			$h = $this->scale;

			if (function_exists('imagecreate')) {
				$png = imagecreate($w, ($bcd['maxw'] * $h));
				$bgcol = imagecolorallocate($png, 255, 255, 255);
				// imagecolortransparent($png, $bgcol);
				$fgcol = imagecolorallocate($png, $color[0], $color[1], $color[2]);
			} else {
				return false;
			}
			// print bars
			$y = 0;
			foreach ($bcd['bcode'] as $k => $v) {
				$bw = round(($v['h'] * $w / $bcd['maxh']), 3);
				$bh = round(($v['w'] * $h), 3);
				if ($v['t']) {
					$x = round(($v['p'] * $h / $bcd['maxh']), 3);
					imagefilledrectangle($png, $x, $y, ($x + $bw - 1), ($y + $bh - 1), $fgcol);
				}
				$y += $bh;
			}
		}

		if ($this->file === null) {
			ob_start();
			imagepng($png);
			$barcodeData = ob_get_clean();
		} else {
			preg_match("/\.png$/", $this->file, $output);
			if (count($output) == 0)
				throw new \Exception('Incorrect file extension format.');
			
			$filePath = explode(DIRECTORY_SEPARATOR, $this->file);
			if (count($filePath) == 1 ) {
				$savePath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$this->file;
			} else {
				$savePath = $this->file;
			}
			
			if (!@imagepng($png, $savePath))
				throw new \Exception('Not found save path.');

			$barcodeData = file_get_contents($savePath);			
		}

		imagedestroy($png);

		return $barcodeData;
	}

	public function getBarcodeSvgData()
	{
		$bcd = $this->barcode->getBarcodeArray();
		$rotate = $this->rotate;
		$color = $this->hexColor;

		if ($rotate == 0 || $rotate%180 == 0) {
			$w = $this->scale;
			$h = $this->height;

			$repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');
			$barcodeData = '<'.'?'.'xml version="1.0" standalone="no"'.'?'.'>'."\n";
			$barcodeData .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n";
			$barcodeData .= '<svg width="'.round(($bcd['maxw'] * $w), 3).'" height="'.$h.'" version="1.1" xmlns="http://www.w3.org/2000/svg">'."\n";
			$barcodeData .= "\t".'<desc>'.strtr($bcd['code'], $repstr).'</desc>'."\n";
			$barcodeData .= "\t".'<g id="bars" fill="'.$color.'" stroke="none">'."\n";
			// print bars
			$x = 0;
			foreach ($bcd['bcode'] as $k => $v) {
				$bw = round(($v['w'] * $w), 3);
				$bh = round(($v['h'] * $h / $bcd['maxh']), 3);
				if ($v['t']) {
					$y = round(($v['p'] * $h / $bcd['maxh']), 3);
					// draw a vertical bar
					$barcodeData .= "\t\t".'<rect x="'.$x.'" y="'.$y.'" width="'.$bw.'" height="'.$bh.'" />'."\n";
				}
				$x += $bw;
			}
			$barcodeData .= "\t".'</g>'."\n";
			$barcodeData .= '</svg>'."\n";
		} else {
			$w = $this->height;
			$h = $this->scale;

			$repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');
			$barcodeData = '<'.'?'.'xml version="1.0" standalone="no"'.'?'.'>'."\n";
			$barcodeData .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n";
			$barcodeData .= '<svg width="'.$w.'" height="'.round(($bcd['maxw'] * $h), 3).'" version="1.1" xmlns="http://www.w3.org/2000/svg">'."\n";
			$barcodeData .= "\t".'<desc>'.strtr($bcd['code'], $repstr).'</desc>'."\n";
			$barcodeData .= "\t".'<g id="bars" fill="'.$color.'" stroke="none">'."\n";
			// print bars
			$y = 0;
			foreach ($bcd['bcode'] as $k => $v) {
				$bw = round(($v['h'] * $w / $bcd['maxh']), 3);
				$bh = round(($v['w'] * $h), 3);
				if ($v['t']) {
					$x = round(($v['p'] * $h / $bcd['maxh']), 3);
					// draw a vertical bar
					$barcodeData .= "\t\t".'<rect x="'.$x.'" y="'.$y.'" width="'.$bw.'" height="'.$bh.'" />'."\n";
				}
				$y += $bh;
			}
			$barcodeData .= "\t".'</g>'."\n";
			$barcodeData .= '</svg>'."\n";
		}

		if ($this->file != null) {
			preg_match("/\.svg$/", $this->file, $output);
			if (count($output) == 0)
				throw new \Exception('Incorrect file extension format.');
			
			$filePath = explode(DIRECTORY_SEPARATOR, $this->file);
			if (count($filePath) == 1 ) {
				$savePath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$this->file;
			} else {
				$savePath = $this->file;
			}
			
			if (!@file_put_contents($savePath, $barcodeData))
				throw new \Exception('Not found save path.');
		}

		return $barcodeData;
	}

	public function renderHTML()
	{
		$barcodeData = $this->getBarcodeHtmlData();

		header('Content-Type: text/html');
		header('Content-Length: '.strlen($barcodeData));
		header('Cache-Control: no-cache');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

		echo $barcodeData;
	}

	public function renderPNG()
	{
		$barcodeData = $this->getBarcodePngData();
			
		header('Content-Type: image/png');
		header('Content-Length: '.strlen($barcodeData));
		header('Cache-Control: no-cache');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');			
			
		echo $barcodeData;
	}

	public function renderSVG()
	{
		$barcodeData = $this->getBarcodeSvgData();
		
		header('Content-Type: image/svg+xml');
		header('Content-Length: '.strlen($barcodeData));
		header('Cache-Control: no-cache');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');	

		echo $barcodeData;
	}
}