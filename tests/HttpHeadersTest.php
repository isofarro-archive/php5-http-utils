<?php

require_once dirname(dirname(__file__)) . '/HttpUtils.php';

class HttpHeadersTest extends PHPUnit_Framework_TestCase {
	protected $headers;
	
	function setUp() {
		$this->headers = new HttpHeaders();
	}
	
	function tearDown() {
	
	}


	function testInitHeaders() {
		$this->assertTrue(class_exists('HttpHeaders'));
		$headers = new HttpHeaders();
		$this->assertNotNull($headers);	
	}

	function testSetNewHeader() {
		$this->headers->setHeader('header1', 'value1');
	
		$headerVal = $this->headers->getHeader('header1');
		$this->assertEquals('value1', $headerVal);
	}
	
	function testHasHeader() {
		$this->assertFalse($this->headers->hasHeader('header1'));

		$this->headers->setHeader('header1', 'value1');
		$this->assertTrue($this->headers->hasHeader('header1'));
	}
	
	function testRemoveHeader() {
		$this->headers->setHeader('header1', 'value1');
		$this->assertTrue($this->headers->hasHeader('header1'));
	
		$this->headers->removeHeader('header1');
		$this->assertFalse($this->headers->hasHeader('header1'));
	}

	
	function testRemoveHeader2() {
		$this->headers->setHeader('header1', 'value1');
		$this->assertTrue($this->headers->hasHeader('header1'));
	
		$this->headers->setHeader('header2', 'value2');
		$this->assertTrue($this->headers->hasHeader('header2'));

		$this->headers->removeHeader('header1');

		$this->assertFalse($this->headers->hasHeader('header1'));
		$this->assertTrue($this->headers->hasHeader('header2'));
	}

	function testSetExistingHeader() {
		$this->headers->setHeader('header1', 'value1');
	
		$headerVal = $this->headers->getHeader('header1');
		$this->assertEquals('value1', $headerVal);

		$this->headers->setHeader('header1', 'value2');
		$headerVal = $this->headers->getHeader('header1');
		$this->assertEquals('value2', $headerVal);
	}

	function testAddNewHeaders() {
		$this->headers->setHeader('header1', 'value1');
		
		$this->assertTrue($this->headers->hasHeader('header1'));
		$this->assertEquals('value1', $this->headers->getHeader('header1'));
	
		//echo "Before "; print_r($this->headers);
		$this->headers->setHeader('header2', 'value2');
		//echo "After: "; print_r($this->headers);

		$this->assertTrue($this->headers->hasHeader('header1'));
		$this->assertEquals('value1', $this->headers->getHeader('header1'));
	
		$this->assertTrue($this->headers->hasHeader('header2'));
		$this->assertEquals('value2', $this->headers->getHeader('header2'));
	
	}

}

?>
