<?php

require_once dirname(dirname(__file__)) . '/HttpCache.php';
require_once dirname(dirname(__file__)) . '/HttpUtils.php';

class HttpCacheTest extends PHPUnit_Framework_TestCase {
	protected $cache;
	protected $cacheRoot;

	public function setUp() {
		//$this->cacheRoot = dirname(dirname(__file__)) . 'cache/';
		$this->cacheRoot = '/home/user/cache/httpcache/';
		$this->cache = new HttpCache();
		$this->cache->setRootDir($this->cacheRoot);
	}
	
	public function tearDown() {
		system("rm -rf {$this->cacheRoot}/example.com/*");
	}

	public function testInitCache() {
		$this->assertTrue(class_exists('HttpCache'));
		
		$cacheDir = '/tmp/';
		$this->cache->setRootDir('/tmp/');
		$this->assertEquals($cacheDir, $this->cache->getRootDir());
	}

	public function testCacheGet() {
		$url = 'http://example.com/cache1.html';
		$text = <<<HTML
<html>
<head>
	<title>Cache page 1</title>
</head>
<body>
	<h1>Cache page header 1</h1>
</body>
</html>
HTML;

		$this->assertFalse($this->cache->isCached($url));
		$this->cache->cache($url, $text);
		$this->assertTrue($this->cache->isCached($url));
		
		$cached = $this->cache->get($url);
		$this->assertNotNull($cached);
		$this->assertType('string', $cached);
		$this->assertEquals($text, $cached);
		
		$this->cache->uncache($url);
		$this->assertFalse($this->cache->isCached($url));
	}


}


?>