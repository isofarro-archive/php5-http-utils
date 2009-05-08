<?php

require_once dirname(dirname(__file__)) . '/HttpRequest.php';
require_once dirname(dirname(__file__)) . '/HttpUtils.php';

class HttpRequestTest extends PHPUnit_Framework_TestCase {
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
		$this->assertEquals($url, $this->request->getUrl());
		$this->assertEquals('example.org', $this->request->getHost());
		$this->assertEquals('/path/file.php',   $this->request->getPath());
		
		$query = $this->request->getQuery();
		$this->assertType('array', $query);
		$this->assertArrayHasKey('key1', $query);
		$this->assertEquals('val1', $query['key1']);
		$this->assertArrayHasKey('key2', $query);
		$this->assertEquals('val2', $query['key2']);

		$this->assertEquals('key1=val1&key2=val2', $this->request->getQueryString());
	}

	public function testSetHost() {
		$url      = 'http://example.com/file.php';
		$expected = 'http://example.org/file.php';
		$this->request->setUrl($url);

		$this->assertEquals($url, $this->request->getUrl());
		$this->request->setHost('example.org');
		$this->assertEquals('example.org', $this->request->getHost());
		$this->assertEquals($expected, $this->request->getUrl());
		
	}

	public function testChangePath() {
		$url      = 'http://example.org:8080/path/file.php';
		$expected = 'http://example.org:8080/newfile.php';

		$this->request->setUrl($url);
		$this->assertEquals($url, $this->request->getUrl());

		$this->request->setUrl('/newfile.php');	
		$this->assertEquals($expected, $this->request->getUrl());
	}

	public function testRelativeChangePath() {
		$url      = 'http://example.org:8080/path/file.php';
		$expected = 'http://example.org:8080/path/newfile.php';

		$this->request->setUrl($url);
		$this->assertEquals($url, $this->request->getUrl());

		$this->request->setRelativeUrl('newfile.php');	
		$this->assertEquals($expected, $this->request->getUrl());
	}

}

?>
