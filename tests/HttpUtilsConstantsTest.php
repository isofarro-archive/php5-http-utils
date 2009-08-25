<?php

require_once dirname(dirname(__FILE__)) . '/HttpUtils.php';

class HttpUtilsConstantsTest extends PHPUnit_Framework_TestCase {
	var $dirs = array('/tmp/unit-test-cache/', '/tmp/unit-test-data/');

	function setUp() {
		foreach($this->dirs as $dir) {
			if (!empty($dir) && !is_dir($dir)) {
				mkdir($dir);
			}
		}
	}
	
	function tearDown() {
		//foreach($this->dirs as $dir) {
		//	if (!empty($dir) && is_dir($dir)) {
		//		rmdir($dir);
		//	}
		//}
	}
	
	public function testDefaultCacheDir() {
		$cacheDir = HttpUtilsConstants::getBaseCacheDir();
		
		if (is_dir('/var/cache/isolani/http-cache/')) {
			$this->assertEquals('/var/cache/isolani/http-cache/', $cacheDir);
		} else {
			$this->assertEquals('/tmp/http-cache/', $cacheDir);
		}
	}

	public function testDefaultDataDir() {
		$cacheDir = HttpUtilsConstants::getBaseDataDir();
		
		if (is_dir('/home/user/data/')) {
			$this->assertEquals('/home/user/data/', $cacheDir);
		} else {
			$this->assertEquals('/tmp/http-data/', $cacheDir);
		}
	}

	public function testCustomCacheDir() {
		$dir = '/tmp/unit-test-cache/';
		
		//$this->assertFalse(is_dir($dir));
		//$result = HttpUtilsConstants::setBaseCacheDir($dir);
		//$this->assertFalse($result);
		//mkdir($dir);

		$this->assertTrue(is_dir($dir));

		$result = HttpUtilsConstants::setBaseCacheDir($dir);
		$this->assertTrue($result);
		
		$cacheDir = HttpUtilsConstants::getBaseCacheDir();
		$this->assertEquals($dir, $cacheDir);
		
		rmdir($dir);
	}

	public function testCustomDataDir() {
		$dir = '/tmp/unit-test-data/';
		
		//$this->assertFalse(is_dir($dir));
		//$result = HttpUtilsConstants::setBaseDataDir($dir);
		//$this->assertFalse($result);
		//mkdir($dir);

		$this->assertTrue(is_dir($dir));

		$result = HttpUtilsConstants::setBaseDataDir($dir);
		$this->assertTrue($result);
		
		$dataDir = HttpUtilsConstants::getBaseDataDir();
		$this->assertEquals($dir, $dataDir);
		
		rmdir($dir);
	}
}

?>