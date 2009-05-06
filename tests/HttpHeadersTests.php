<?php

require_once '../HttpUtils.php';

class HttpHeadersTests extends PHPUnit_Framework_TestCase {
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

	function testAddHeader() {
		$this->headers->addHeader('header1', 'value1');
	
		$headerVal = $this->headers->getHeader('header1');
		$this->assertEquals('value1', $headerVal);
	}
	


}

?>