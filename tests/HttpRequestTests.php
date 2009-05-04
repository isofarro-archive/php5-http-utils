<?php

require_once '../HttpRequest.php';
require_once '../HttpUtils.php';

class HttpRequestTests extends PHPUnit_Framework_TestCase {
	var $request;

	public function setUp() {
		$this->request = new HttpRequest();
	}
	
	public function tearDown() {
	
	}
	
	
	public function testInitRequest() {
		$this->assertTrue(class_exists('HttpRequest'));
		$request = new HttpRequest();
		$this->assertNotNull($request);
	}
	
	public function testSetUrl() {
		$url = 'http://example.org:8080/path/file.php?key1=val1&key2=val2#place';
		$this->request->setUrl($url);
		
		$this->assertNotNull($this->request->getUrl());
	}

}

?>