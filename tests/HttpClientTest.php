<?php

require_once '../HttpClient.php';
require_once '../HttpRequest.php';
require_once '../HttpResponse.php';
require_once '../HttpUtils.php';

class HttpClientTest extends PHPUnit_Framework_TestCase {
	var $http;

	public function setUp() {
		$this->http = new HttpClient();
	}
	
	public function tearDown() {
		$this->http = NULL;
	}
	
	public function testInitHttpClient() {
		$this->assertTrue(class_exists('HttpClient'));
	
	}


	public function testSimpleGet() {
		$url = 'http://yahoo.com/';
		
		$response = $this->http->getUrl($url);
		$this->assertNotNull($response);
		$this->assertTrue(is_string($response));
		
	}
}

?>
