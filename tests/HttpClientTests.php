<?php

require_once '../HttpClient.php';

class HttpClientTests extends PHPUnit_Framework_TestCase {

	public function setUp() {
	
	}
	
	public function tearDown() {
	
	}
	
	public function testInitHttpClient() {
		$this->assertTrue(class_exists('HttpClient'));
	
	}

}

?>