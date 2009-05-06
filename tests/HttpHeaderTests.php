<?php

require_once '../HttpUtils.php';

class HttpHeaderTests extends PHPUnit_Framework_TestCase {
	protected $header;
	
	function setUp() {
		$this->header = new HttpHeader();
	}
	
	function tearDown() {
	
	}

	function testInitHeaders() {
		$this->assertTrue(class_exists('HttpHeader'));
		$header = new HttpHeader();
		$this->assertNotNull($header);	
	}
	
	function testConstructHeader() {
		$this->header = new HttpHeader('header1', 'value1');
	
		$headerName = $this->header->getName();
		$this->assertEquals('header1', $headerName);

		$headerValue = $this->header->getValue();
		$this->assertEquals('value1', $headerValue);
	}
	

	function testSetHeader() {
		$this->header->setHeader('header1', 'value1');
	
		$headerName = $this->header->getName();
		$this->assertEquals('header1', $headerName);

		$headerValue = $this->header->getValue();
		$this->assertEquals('value1', $headerValue);
	}
	
	function testChangeHeader() {
		$this->header->setHeader('header1', 'value1');

		$this->assertEquals('header1', $this->header->getName());		
		$this->assertEquals('value1',  $this->header->getValue());
		$this->assertEquals('header1: value1', $this->header->getHeader());

		$headerArray = $this->header->getHeaderArray();
		$this->assertEquals('header1', $headerArray[0]);
		$this->assertEquals('value1',  $headerArray[1]);

		// Change header name
		$this->header->setName('header2');
		$this->assertEquals('header2', $this->header->getName());		
		$this->assertEquals('value1',  $this->header->getValue());		
		$this->assertEquals('header2: value1', $this->header->getHeader());

		$headerArray = $this->header->getHeaderArray();
		$this->assertEquals('header2', $headerArray[0]);
		$this->assertEquals('value1',  $headerArray[1]);
		
		// Change header value
		$this->header->setValue('value2');
		$this->assertEquals('header2', $this->header->getName());		
		$this->assertEquals('value2',  $this->header->getValue());		
		$this->assertEquals('header2: value2', $this->header->getHeader());

		$headerArray = $this->header->getHeaderArray();
		$this->assertEquals('header2', $headerArray[0]);
		$this->assertEquals('value2',  $headerArray[1]);
	}

	public function testValueArray() {
		$list = array('value1', 'value2', 'value3');
		$this->header->setHeader('header1', $list);
		
		$this->assertEquals('header1: value1,value2,value3', $this->header->getHeader());
	
		$headerArray = $this->header->getHeaderArray();
		$this->assertEquals('header1', $headerArray[0]);
		$this->assertType('array',  $headerArray[1]);
	}
}

?>