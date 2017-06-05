<?php

use jucksearm\barcode\Datamatrix;
 
class DatamatrixTest extends PHPUnit_Framework_TestCase {
 
 	private $class;
 	private $tmpDir;

    function setUp() {
        $this->class = new Datamatrix;
        $this->tmpDir = dirname(dirname(__FILE__)).'/tmp';
    }

    function testHasTmpDirectory()
    {
    	$this->assertTrue(is_dir($this->tmpDir));
    }

    function testDatamatrixFactory() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setFile(null)
		  ->setSize(null)
		  ->setMargin(null)
		  ->setColor(null)
		  ->getBarcode()
		  ->getBarcodeArray();

		$this->assertNotEmpty($data);
    }

    function testCreateHtmlDatamatrix() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->getDatamatrixHtmlData();

		$this->assertNotEmpty($data);
    }

    function testCreatePngDatamatrix() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
    	  ->getDatamatrixPngData();
		  
		$this->assertNotEmpty($data);
    }

    function testCreatePngDatamatrixAndSave() {
    	$file = 'testPng.png';
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setFile($file)
		  ->getDatamatrixPngData();
		
		$this->assertTrue(is_file($this->tmpDir.DIRECTORY_SEPARATOR.$file));

		unlink($this->tmpDir.DIRECTORY_SEPARATOR.$file);
    }

    function testCreateSvgDatamatrix() {
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->getDatamatrixSvgData();
		  
		$this->assertNotEmpty($data);
    }

    function testCreateSvgDatamatrixAndSave() {
    	$file = 'testSvg.svg';
    	$data = $this->class->factory()->setCode('https://github.com/jucksearm/php-barcode')
		  ->setFile($file)
		  ->getDatamatrixSvgData();
		
		$this->assertTrue(is_file($this->tmpDir.DIRECTORY_SEPARATOR.$file));
		
		unlink($this->tmpDir.DIRECTORY_SEPARATOR.$file);
    }
}