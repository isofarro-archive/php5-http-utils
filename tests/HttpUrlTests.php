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
		
		
		$this->assertEquals('http', $this->httpUrl->getScheme());
		$this->assertEquals('user', $this->httpUrl->getUser());
		$this->assertEquals('password', $this->httpUrl->getPass());
		$this->assertEquals('example.org', $this->httpUrl->getHost());
		$this->assertEquals('8080', $this->httpUrl->getPort());
		$this->assertEquals('/path/file.php', $this->httpUrl->getPath());
		$this->assertEquals('key1=val1&key2=val2', $this->httpUrl->getQueryString());
		$this->assertEquals('place', $this->httpUrl->getFragment());

		$query = $this->httpUrl->getQuery();
		$this->assertType('array', $query);

		$this->assertArrayHasKey('key1', $query);
		$this->assertEquals('val1', $query['key1']);
		$this->assertArrayHasKey('key2', $query);
		$this->assertEquals('val2', $query['key2']);

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
		$url = 'http://example.org/index.php?page=HomePage';
		$this->httpUrl->setUrl($url);

		$query = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('page', $query);
		$this->assertEquals('HomePage', $query['page']);

		// Now add a new query string
		$this->httpUrl->addQuery(array(		
			'var2' => 'key2',
			'var3' => 'key3'
		));
		$query2 = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('page', $query2);
		$this->assertEquals('HomePage', $query2['page']);
		$this->assertArrayHasKey('var2', $query2);
		$this->assertEquals('key2', $query2['var2']);
		$this->assertArrayHasKey('var3', $query2);
		$this->assertEquals('key3', $query2['var3']);
	}


	function testAddQueryString4() {
		$url = 'http://example.org/index.php?page=HomePage';
		$this->httpUrl->setUrl($url);

		$query = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('page', $query);
		$this->assertEquals('HomePage', $query['page']);

		// Now add a new query string
		$this->httpUrl->addQuery('var2', 0);
		$query2 = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('var2', $query2);
		$this->assertEquals(0, $query2['var2']);
		$this->assertArrayHasKey('page', $query2);
		$this->assertEquals('HomePage', $query2['page']);
	}


	function testAddQueryString5() {
		$url = 'http://example.org/index.php?HomePage';
		$this->httpUrl->setUrl($url);

		$query = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('HomePage', $query);
		$this->assertEquals('', $query['HomePage']);

		// Now add a new query string
		$this->httpUrl->addQuery('var2', 'key2');
		$query2 = $this->httpUrl->getQuery();
		$this->assertArrayHasKey('var2', $query2);
		$this->assertEquals('key2', $query2['var2']);
		$this->assertArrayHasKey('HomePage', $query2);
		$this->assertEquals('', $query2['HomePage']);
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



	function testChangeScheme() {
		$url      = 'http://example.org/file.php';
		$expected = 'https://example.org/file.php';
		$this->httpUrl->setUrl($url);	

		// Valid change
		$this->httpUrl->setScheme('https');
		$this->assertEquals('https', $this->httpUrl->getScheme());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
		
		// invalid change
		$this->httpUrl->setScheme('httpZ');
		$this->assertEquals('https', $this->httpUrl->getScheme());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
	}


	function testChangeUser() {
		$url      = 'http://example.org/file.php';
		$expected = 'http://joebloggs@example.org/file.php';
		$this->httpUrl->setUrl($url);	

		// Valid change
		$this->httpUrl->setUser('joebloggs');
		$this->assertEquals('joebloggs', $this->httpUrl->getUser());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
		
		// invalid change
		$this->httpUrl->setUser('joe@bloggs');
		$this->assertEquals('joebloggs', $this->httpUrl->getUser());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
	}


	function testChangePass() {
		$url      = 'http://joebloggs@example.org/file.php';
		$expected = 'http://joebloggs:qwerty@example.org/file.php';
		$this->httpUrl->setUrl($url);	

		// Valid change
		$this->httpUrl->setPass('qwerty');
		$this->assertEquals('qwerty', $this->httpUrl->getPass());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
		
		// invalid change
		$this->httpUrl->setPass('joe@bloggs');
		$this->assertEquals('qwerty', $this->httpUrl->getPass());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
		
		// nuke password
		$this->httpUrl->setPass(NULL);
		$this->assertEquals('', $this->httpUrl->getPass());
		$this->assertEquals($url, $this->httpUrl->getUrl());

		// Valid change
		$this->httpUrl->setPass('qwerty');
		$this->assertEquals('qwerty', $this->httpUrl->getPass());
		$this->assertEquals($expected, $this->httpUrl->getUrl());

		// Nuke user		
		$this->httpUrl->setUser(NULL);
		$this->assertEquals('', $this->httpUrl->getUser());
		$this->assertEquals('', $this->httpUrl->getPass());
		$this->assertEquals('http://example.org/file.php', $this->httpUrl->getUrl());
	}

	function testChangePort() {
		$url      = 'http://example.org/file.php';
		$expected = 'http://example.org:8080/file.php';
		$this->httpUrl->setUrl($url);	

		// Valid change
		$this->httpUrl->setPort('8080');
		$this->assertEquals('8080', $this->httpUrl->getPort());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
		
		// invalid change
		$this->httpUrl->setPort('ALL');
		$this->assertEquals('8080', $this->httpUrl->getPort());
		$this->assertEquals($expected, $this->httpUrl->getUrl());


		// nuke port
		$this->httpUrl->setPort(NULL);
		$this->assertEquals('', $this->httpUrl->getPort());
		$this->assertEquals($url, $this->httpUrl->getUrl());
	}


	function testChangeHost() {
		$url      = 'http://example.org/file.php';
		$expected = 'http://example.com/file.php';
		$this->httpUrl->setUrl($url);	

		// Valid change
		$this->httpUrl->setHost('example.com');
		$this->assertEquals('example.com', $this->httpUrl->getHost());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
		
		// invalid change
		$this->httpUrl->setHost('example..com');
		$this->assertEquals('example.com', $this->httpUrl->getHost());
		$this->assertEquals($expected, $this->httpUrl->getUrl());

	}


	function testChangePath() {
		$url      = 'http://example.org/file.php';
		$expected = 'http://example.org/audio';
		$this->httpUrl->setUrl($url);	

		// Valid change
		$this->httpUrl->setPath('/audio');
		$this->assertEquals('/audio', $this->httpUrl->getPath());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
		
		// invalid change
		$this->httpUrl->setPath('/aud?o');
		$this->assertEquals('/audio', $this->httpUrl->getPath());
		$this->assertEquals($expected, $this->httpUrl->getUrl());

	}


	function testChangeFragment() {
		$url      = 'http://example.org/file.php';
		$expected = 'http://example.org/file.php#anchor_top';
		$this->httpUrl->setUrl($url);	

		// Valid change
		$this->httpUrl->setFragment('anchor_top');
		$this->assertEquals('anchor_top', $this->httpUrl->getFragment());
		$this->assertEquals($expected, $this->httpUrl->getUrl());
		
		// nuke change
		$this->httpUrl->setFragment(NULL);
		$this->assertEquals('', $this->httpUrl->getFragment());
		$this->assertEquals($url, $this->httpUrl->getUrl());


	}

}


?>