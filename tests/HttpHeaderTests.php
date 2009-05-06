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
	


}

?>