<?php

use jucksearm\barcode\Barcode;
 
class BarcodeTest extends PHPUnit_Framework_TestCase {
 
 	private $class;
 	private $tmpDir;

    function setUp() {
        $this->class = new Barcode;
        $this->tmpDir = dirname(dirname(__FILE__)).'/tmp';
    }

    function testHasTmpDirectory()
    {
    	$this->assertTrue(is_dir($this->tmpDir));
    }

    function testBarcodeFactory() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setType('C128')
		  ->setFile(null)
		  ->setScale(null)
		  ->setHeight(null)
		  ->setRotate(null)
		  ->setColor(null)
		  ->getBarcode()
		  ->getBarcodeArray();

		$this->assertNotEmpty($data);
    }

    function testCreateHtmlBarcode() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setType('C128')
		  ->getBarcodeHtmlData();

		$this->assertNotEmpty($data);
    }

    function testCreatePngBarcode() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setType('C128')
		  ->getBarcodePngData();
		  
		$this->assertNotEmpty($data);
    }

    function testCreatePngBarcodeAndSave() {
    	$file = 'testPng.png';
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setType('C128')
		  ->setFile($file)
		  ->getBarcodePngData();
		
		$this->assertTrue(is_file($this->tmpDir.DIRECTORY_SEPARATOR.$file));

		unlink($this->tmpDir.DIRECTORY_SEPARATOR.$file);
    }

    function testCreateSvgBarcode() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setType('C128')
		  ->getBarcodeSvgData();
		  
		$this->assertNotEmpty($data);
    }

    function testCreateSvgBarcodeAndSave() {
    	$file = 'testSvg.svg';
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setType('C128')
		  ->setFile($file)
		  ->getBarcodeSvgData();
		
		$this->assertTrue(is_file($this->tmpDir.DIRECTORY_SEPARATOR.$file));
		
		unlink($this->tmpDir.DIRECTORY_SEPARATOR.$file);
    }
}