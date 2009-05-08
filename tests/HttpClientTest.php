<?php

require_once dirname(dirname(__file__)) . '/HttpClient.php';
require_once dirname(dirname(__file__)) . '/HttpRequest.php';
require_once dirname(dirname(__file__)) . '/HttpResponse.php';
require_once dirname(dirname(__file__)) . '/HttpUtils.php';

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
		$url = 'http://www.yahoo.com';
		
		$response = $this->http->getUrl($url);
		$this->assertNotNull($response);
		$this->assertTrue(is_string($response));
	}
	
	public function testSimpleNoRedirectGet() {
		$url = 'http://yahoo.com/';
		
		$response = $this->http->getUrl($url);
		$this->assertNull($response);
	}

	public function testSimpleRedirectGet() {
		$url = 'http://yahoo.com/';
		
		$this->http->setFollowRedirect(true);
		$response = $this->http->getUrl($url);
		$this->assertNotNull($response);
		$this->assertTrue(is_string($response));
	}

	public function testSimpleGetPageNotFound() {
		$url = 'http://eurosport.yahoo.com/this_page_doesnt_exist.html';
		$response = $this->http->getUrl($url);
		$this->assertNull($response);
	}
	
	
	public function testDoGetRequest() {
		$request = new HttpRequest();
		$request->setUrl('http://uk.yahoo.com/');
		
		$response = $this->http->doRequest($request);

		//print_r($response);
		$this->assertEquals(200, $response->getStatus());
		$this->assertNotNull($response->getBody());
		$this->assertTrue(is_string($response->getBody()));
		
	}

}

?>
