<?php

namespace jucksearm\barcode\lib;

use jucksearm\barcode\lib\Barcode2D;

class PDF417Factory
{
	private $_attributes;
	private $_borderPx = 1;
	private $_marginPx = 5;
	private $_scalePx = 1;

	public function __construct()
	{
		$this->_attributes = [
			'code'   => null,
			'file'   => null,
			'size'   => 100,
			'margin' => 1,
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

	public function setFile($value)
	{
		$this->setAttribute('file', $value);
		return $this;
	}

	public function getFile()
	{
		return $this->getAttribute('file');
	}

	public function setSize($value)
	{
		$this->setAttribute('size', $value);
		return $this;
	}

	public function getSize()
	{
		return $this->getAttribute('size') * $this->_scalePx;
	}

	public function setMargin($value)
	{
		$this->setAttribute('margin', $value);
		return $this;
	}

	public function getMargin()
	{
		return $this->getAttribute('margin');
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
		return new Barcode2D($this->code, 'PDF417');
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

	private function getScale()
	{
		$scale = [];
		$bcd = $this->barcode->getBarcodeArray();

		if ($this->size < $bcd['num_cols'])
			throw new \Exception('This size not render.');

		if ($this->margin > 0) { 
			$scale['frameX'] = $this->size - ($this->_borderPx * 2);
			$scale['margin'] = round($this->size * ($this->margin / 100));
			$scale['area'] = $scale['frameX'] - (($scale['margin'] * 2));
			$scale['size'] = floor($scale['area'] / $bcd['num_cols']);
			$scale['posX'] = $scale['margin'] + floor(($scale['area'] - ($bcd['num_cols'] * $scale['size'])) / 2);
			$scale['posY'] = $scale['posX'];
			$scale['frameY'] = ($bcd['num_rows'] * $scale['size']) + ($scale['posY'] * 2);
		} else {
			$scale['frameX'] = $this->size;
			$scale['margin'] = 0;
			$scale['area'] = $this->size;
			$scale['size'] = floor($scale['area'] / $bcd['num_cols']);
			$scale['posX'] = floor(($scale['frameX'] - ($bcd['num_cols'] * $scale['size'])) / 2);
			$scale['posY'] = $scale['posX'];
			$scale['frameY'] = ($bcd['num_rows'] * $scale['size']) + ($scale['posY'] * 2);
		}		
// exit;
		
		
		return (object) $scale;
	}

	public function getPDF417HtmlData()
	{
		$bcd = $this->barcode->getBarcodeArray();
		$color = $this->hexColor;
		$scale = $this->scale;

		if ($this->margin > 0) {
			$pdf417Data = '<div style="position:absolute; border: solid '.$this->_borderPx.'px '.$this->hexColor.'; width:'.$scale->frameX.'px; height:'.$scale->frameY.'px">';
		} else {
			$pdf417Data = '<div style="position:absolute; width:'.$scale->frameX.'px; height:'.$scale->frameY.'px">';
		}
		
		$pdf417Data .= '<div style="font-size:0;position:absolute; width:'.($scale->size * $bcd['num_cols']).'px;height:'.($scale->size * $bcd['num_rows']).'px; top:'.$scale->posY.'px; left:'.$scale->posX.'px" z-index:1;>'."\n";
		
		$w = $scale->size;
		$h = $scale->size;

		// print bars
		$y = 0;
		// for each row
		for ($r = 0; $r < $bcd['num_rows']; ++$r) {
			$x = 0;
			// for each column
			for ($c = 0; $c < $bcd['num_cols']; ++$c) {
				if ($bcd['bcode'][$r][$c] == 1) {
					// draw a single barcode cell
					$pdf417Data .= '<div style="background-color:'.$color.';width:'.$w.'px;height:'.$h.'px;position:absolute;left:'.$x.'px;top:'.$y.'px;">&nbsp;</div>'."\n";
				}
				$x += $w;
			}
			$y += $h;
		}
		
		$pdf417Data .= '</div>'."\n";
		
		$pdf417Data .= '</div>'."\n";

		return $pdf417Data;
	}

	public function getPDF417PngData()
	{
		$bcd = $this->barcode->getBarcodeArray();
		$this->_borderPx = 0;
		$color = $this->rgbColor;
		$scale = $this->scale;

		$w = $bcd['num_cols'] * $scale->size;
		$h = $bcd['num_rows'] * $scale->size;
		
		if (function_exists('imagecreate')) {
			$png = imagecreate($w, $h);
			$bgcol = imagecolorallocate($png, 255, 255, 255);
			imagecolortransparent($png, $bgcol);
			$fgcol = imagecolorallocate($png, $color[0], $color[1], $color[2]);
		} else {
			return false;
		}

		// print bars
		$y = 0;
		// for each row
		for ($r = 0; $r < $bcd['num_rows']; ++$r) {
			$x = 0;
			// for each column
			for ($c = 0; $c < $bcd['num_cols']; ++$c) {
				if ($bcd['bcode'][$r][$c] == 1) {
					imagefilledrectangle($png, $x, $y, ($x + $scale->size - 1), ($y + $scale->size - 1), $fgcol);
				}
				$x += $scale->size;
			}
			$y += $scale->size;
		}

		$frame = imagecreatetruecolor($scale->frameX, $scale->frameY);
		$blank = imagecreate($this->size, $this->size);
		imagecolorallocate($blank, 255, 255, 255);
		imagecopyresampled($frame, $blank, 0, 0, 0, 0, imagesx($blank), imagesy($blank), imagesx($blank), imagesy($blank));
		imagedestroy($blank);
		imagecopyresampled($frame, $png, $scale->posX, $scale->posY, 0, 0, imagesx($png), imagesy($png), imagesx($png), imagesy($png));
		imagedestroy($png);

		if ($this->file === null) {
			ob_start();
			imagepng($frame);
			$pdf417Data = ob_get_clean();
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
			
			if (!@imagepng($frame, $savePath))
				throw new \Exception('Not found save path.');

			$pdf417Data = file_get_contents($savePath);
		}

		imagedestroy($frame);
		
		return $pdf417Data;
	}

	public function getPDF417SvgData()
	{
		$bcd = $this->barcode->getBarcodeArray();
		$color = $this->hexColor;
		$scale = $this->scale;

		$w = $bcd['num_cols'] * $scale->size;
		$h = $bcd['num_rows'] * $scale->size;

		$repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');
		$pdf417Data = '<'.'?'.'xml version="1.0" standalone="no"'.'?'.'>'."\n";
		$pdf417Data .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n";
		$pdf417Data .= '<svg width="'.$scale->frameX.'" height="'.$scale->frameY.'" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'."\n";
		$pdf417Data .= "\t".'<desc>'.strtr($bcd['code'], $repstr).'</desc>'."\n";

		if ($this->margin > 0) {
			$pdf417Data .= "\t".'<g id="border" fill="none" stroke="'.$color.'">'."\n";
			$pdf417Data .= "\t\t".'<rect height="'.$scale->frameY.'" width="'.$scale->frameX.'" y="0" x="0" />'."\n";
			$pdf417Data .= "\t".'</g>'."\n";
		}

		$pdf417Data .= "\t".'<g id="bars" fill="'.$color.'" stroke="none">'."\n";
		// print bars
		$y = $scale->posY;
		// for each row
		for ($r = 0; $r < $bcd['num_rows']; ++$r) {
			$x = $scale->posX;
			// for each column
			for ($c = 0; $c < $bcd['num_cols']; ++$c) {
				if ($bcd['bcode'][$r][$c] == 1) {
					// draw a single barcode cell
					$pdf417Data .= "\t\t".'<rect x="'.$x.'" y="'.$y.'" width="'.$scale->size.'" height="'.$scale->size.'" />'."\n";
				}
				$x += $scale->size;
			}
			$y += $scale->size;
		}
		$pdf417Data .= "\t".'</g>'."\n";

		$pdf417Data .= '</svg>'."\n";
		
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
			
			if (!@file_put_contents($savePath, $pdf417Data))
				throw new \Exception('Not found save path.');
		}

		return $pdf417Data;
	}

	public function renderHTML()
	{
		$pdf417Data = $this->getPDF417HtmlData();

		header('Content-Type: text/html');
		header('Content-Length: '.strlen($pdf417Data));
		header('Cache-Control: no-cache');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

		echo $pdf417Data;
	}

	public function renderPNG()
	{
		$pdf417Data = $this->getPDF417PngData();

		header('Content-Type: image/png');
		header('Content-Length: '.strlen($pdf417Data));
		header('Cache-Control: no-cache');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');			
			
		echo $pdf417Data;
	}

	public function renderSVG()
	{
		$pdf417Data = $this->getPDF417SvgData();
		
		header('Content-Type: image/svg+xml');
		header('Content-Length: '.strlen($pdf417Data));
		header('Cache-Control: no-cache');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');	

		echo $pdf417Data;
	}
}