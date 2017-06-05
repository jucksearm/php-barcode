<?php

use jucksearm\barcode\QRcode;
 
class QRcodeTest extends PHPUnit_Framework_TestCase {
 
 	private $class;
 	private $tmpDir;

    function setUp() {
        $this->class = new QRcode;
        $this->tmpDir = dirname(dirname(__FILE__)).'/tmp';
    }

    function testHasTmpDirectory()
    {
    	$this->assertTrue(is_dir($this->tmpDir));
    }

    function testQRcodeFactory() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setEmblem(null)
		  ->setLevel(null)
		  ->setFile(null)
		  ->setSize(null)
		  ->setMargin(null)
		  ->setColor(null)
		  ->getBarcode()
		  ->getBarcodeArray();

		$this->assertNotEmpty($data);
    }

    function testCreateHtmlQRcode() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->getQRcodeHtmlData();

		$this->assertNotEmpty($data);
    }

    function testCreatePngQRcode() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
    	  ->getQRcodePngData();
		  
		$this->assertNotEmpty($data);
    }

    function testCreatePngQRcodeAndSave() {
    	$file = 'testPng.png';
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setFile($file)
		  ->getQRcodePngData();
		
		$this->assertTrue(is_file($this->tmpDir.DIRECTORY_SEPARATOR.$file));
        
		unlink($this->tmpDir.DIRECTORY_SEPARATOR.$file);
    }

    function testCreateSvgQRcode() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->getQRcodeSvgData();
		  
		$this->assertNotEmpty($data);
    }

    function testCreateSvgQRcodeAndSave() {
    	$file = 'testSvg.svg';
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setFile($file)
		  ->getQRcodeSvgData();
		
		$this->assertTrue(is_file($this->tmpDir.DIRECTORY_SEPARATOR.$file));
		
		unlink($this->tmpDir.DIRECTORY_SEPARATOR.$file);
    }
}