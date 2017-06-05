<?php

use jucksearm\barcode\PDF417;
 
class PDF417Test extends PHPUnit_Framework_TestCase {
 
 	private $class;
 	private $tmpDir;

    function setUp() {
        $this->class = new PDF417;
        $this->tmpDir = dirname(dirname(__FILE__)).'/tmp';
    }

    function testHasTmpDirectory()
    {
    	$this->assertTrue(is_dir($this->tmpDir));
    }

    function testPDF417Factory() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setFile(null)
		  ->setSize(null)
		  ->setMargin(null)
		  ->setColor(null)
		  ->getBarcode()
		  ->getBarcodeArray();

		$this->assertNotEmpty($data);
    }

    function testCreateHtmlPDF417() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->getPDF417HtmlData();

		$this->assertNotEmpty($data);
    }

    function testCreatePngPDF417() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
    	  ->getPDF417PngData();
		  
		$this->assertNotEmpty($data);
    }

    function testCreatePngPDF417AndSave() {
    	$file = 'testPng.png';
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setFile($file)
		  ->getPDF417PngData();
		
		$this->assertTrue(is_file($this->tmpDir.DIRECTORY_SEPARATOR.$file));

		unlink($this->tmpDir.DIRECTORY_SEPARATOR.$file);
    }

    function testCreateSvgPDF417() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->getPDF417SvgData();
		  
		$this->assertNotEmpty($data);
    }

    function testCreateSvgPDF417AndSave() {
    	$file = 'testSvg.svg';
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setFile($file)
		  ->getPDF417SvgData();
		
		$this->assertTrue(is_file($this->tmpDir.DIRECTORY_SEPARATOR.$file));
		
		unlink($this->tmpDir.DIRECTORY_SEPARATOR.$file);
    }
}