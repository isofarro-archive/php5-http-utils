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


	function testUpdateQueryString() {
		$url = 'http://example.org/index.php';
		$this->httpUrl->setUrl($url);
		
		$qs = 'HomePage';
		$expected = 'http://example.org/index.php?HomePage';
		$this->httpUrl->setQuery($qs);

		$query = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('HomePage', $query);
		$this->assertEquals('', $query['HomePage']);

		$url2 = $this->httpUrl->getUrl();
		$this->assertNotNull($url2);
		$this->assertEquals($expected, $url2);

		$qs = 'page=HomePage';
		$expected = 'http://example.org/index.php?page=HomePage';
		$this->httpUrl->setQuery($qs);

		$query = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('page', $query);
		$this->assertEquals('HomePage', $query['page']);

		$url3 = $this->httpUrl->getUrl();
		$this->assertNotNull($url3);
		$this->assertEquals($expected, $url3);
	}


	function testAddQueryString1() {
		$url = 'http://example.org/index.php?page=HomePage';
		$this->httpUrl->setUrl($url);

		$query = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('page', $query);
		$this->assertEquals('HomePage', $query['page']);

		// Now add a new query string
		$this->httpUrl->addQuery('var2=key2');
		$query2 = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('var2', $query2);
		$this->assertEquals('key2', $query2['var2']);
		$this->assertArrayHasKey('page', $query2);
		$this->assertEquals('HomePage', $query2['page']);
	}


	function testAddQueryString2() {
		$url = 'http://example.org/index.php?page=HomePage';
		$this->httpUrl->setUrl($url);

		$query = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('page', $query);
		$this->assertEquals('HomePage', $query['page']);

		// Now add a new query string
		$this->httpUrl->addQuery('var2', 'key2');
		$query2 = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('var2', $query2);
		$this->assertEquals('key2', $query2['var2']);
		$this->assertArrayHasKey('page', $query2);
		$this->assertEquals('HomePage', $query2['page']);
	}


	function testAddQueryString3() {
		$url = 'http://example.org/index.php?HomePage';
		$this->httpUrl->setUrl($url);

		$query = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('HomePage', $query);
		$this->assertEquals('', $query['HomePage']);

	}


	function testSetAbsoluteUrl() {
		$url = 'http://www.example.com/path/to/file.html';
		$this->httpUrl->setUrl($url);	

		$this->assertEquals($this->httpUrl->getUrl(), $url);
		
		$url2     = '/newPath/newFile.html';
		$expected = 'http://www.example.com/newPath/newFile.html';
		$this->httpUrl->setUrl($url2);	

		$url3 = $this->httpUrl->getUrl();
		$this->assertEquals($expected, $url3);
	}

	
	function testSetRelativeUrl() {
		$url = 'http://www.example.com/path/to/file.html';
		$this->httpUrl->setUrl($url);	

		$this->assertEquals($this->httpUrl->getUrl(), $url);
		
		$url2     = 'newFile.html';
		$expected = 'http://www.example.com/path/to/newFile.html';
		$this->httpUrl->setRelativeUrl($url2);	
	
		$url3 = $this->httpUrl->getUrl();
		$this->assertEquals($expected, $url3);
	}


	function testSetRelativeUrl2() {
		$url = 'http://www.example.com/path/to/file.html';
		$this->httpUrl->setUrl($url);	

		$this->assertEquals($this->httpUrl->getUrl(), $url);
		
		$url2     = '/newFile.html';
		$expected = 'http://www.example.com/newFile.html';
		$this->httpUrl->setRelativeUrl($url2);	
	
		$url3 = $this->httpUrl->getUrl();
		$this->assertEquals($expected, $url3);
	}


	function testSetRelativeUrl3() {
		$url = 'http://www.example.com/';
		$this->httpUrl->setUrl($url);	

		$this->assertEquals($this->httpUrl->getUrl(), $url);
		
		$url2     = 'newFile.html';
		$expected = 'http://www.example.com/newFile.html';
		$this->httpUrl->setRelativeUrl($url2);	
	
		$url3 = $this->httpUrl->getUrl();
		$this->assertEquals($expected, $url3);
	}

	
	function testQueryString() {
		$qs = array(
			'key1' => 'value1',
			'key2' => 'value2'
		);
		
		$url = 'http://www.example.com/';
		$this->httpUrl->setUrl($url);	
		$this->assertEquals($url, $this->httpUrl->getUrl());

		$this->httpUrl->setQuery($qs);
		$expected = 'http://www.example.com/?key1=value1&key2=value2';
		$url2     = $this->httpUrl->getUrl();
		$this->assertEquals($url2, $expected);
	}


}


?>