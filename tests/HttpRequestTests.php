<?php

require_once '../HttpRequest.php';

class HttpRequestTests extends PHPUnit_Framework_TestCase {
	var $request;

	public function setUp() {
	
	}
	
	public function tearDown() {
	
	}
	
	
	public function testInitRequest() {
		$this->assertTrue(class_exists('HttpRequest'));
		$request = new HttpRequest();
		$this->assertNotNull($request);
	}

}

?>