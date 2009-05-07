<?php

require_once '../HttpResponse.php';
require_once '../HttpUtils.php';

class HttpResponseTest extends PHPUnit_Framework_TestCase {
	protected $response;
	
	public function setUp() {
		$this->response = new HttpResponse();	
	}
	
	public function tearDown() {
	
	}
	
	
	public function testCreateResponse() {
		$response = new HttpResponse();
		$this->assertNotNull($response);
	}

	public function testSetResponseHeaders() {
		$this->response->addHeader('header1', 'value1');
		
		$this->assertTrue($this->response->hasHeader('header1'));
		$this->assertEquals('value1', $this->response->getHeader('header1'));
	
		$this->response->addHeader('header2', 'value2');

		$this->assertTrue($this->response->hasHeader('header1'));
		$this->assertEquals('value1', $this->response->getHeader('header1'));
	
		$this->assertTrue($this->response->hasHeader('header2'));
		$this->assertEquals('value2', $this->response->getHeader('header2'));
	
	}

}

?>
