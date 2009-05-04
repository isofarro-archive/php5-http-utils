<?php

require_once '../HttpUtils.php';

class HttpUrlTests extends PHPUnit_Framework_TestCase {
	protected $httpUrl;
	
	function setUp() {
		$this->httpUrl = new HttpUrl();
	}
	
	function tearDown() {
	
	}
	
	function testInitHttpUrl() {
		$this->assertTrue(class_exists('HttpUrl'));
		$url = new HttpUrl();
		$this->assertNotNull($url);	
	}
	
	function testSetUrl() {
		$url = 'http://user:password@example.org:8080/path/file.php?key1=val1&key2=val2#place';
		$this->httpUrl->setUrl($url);	

		$url2 = $this->httpUrl->getUrl();
		$this->assertNotNull($url2);
		$this->assertType('string', $url2);
		$this->assertEquals($url, $url2);
		

		$url3 = $this->httpUrl->getRawUrl();
		$this->assertNotNull($url3);
		$this->assertType('string', $url3);
		$this->assertEquals($url, $url3);
	}

	function testSetUrl2() {
		$url = 'http://example.org/path/file.php?key1=val1';
		$this->httpUrl->setUrl($url);	

		$url2 = $this->httpUrl->getUrl();
		$this->assertNotNull($url2);
		$this->assertType('string', $url2);
		$this->assertEquals($url, $url2);
		

		$url3 = $this->httpUrl->getRawUrl();
		$this->assertNotNull($url3);
		$this->assertType('string', $url3);
		$this->assertEquals($url, $url3);
	}

	function testSetAbsoluteUrl() {
		$url = 'http://www.example.com/path/to/file.html';
		$this->httpUrl->setUrl($url);	

		$this->assertEquals($this->httpUrl->getUrl(), $url);
		
		$url2 = '/newPath/newFile.html';
		$expected = 'http://www.example.com/newPath/newFile.html';
		$this->httpUrl->setUrl($url2);	

		$url3 = $this->httpUrl->getUrl();
		$this->assertEquals($expected, $url3);

	}
}


?>