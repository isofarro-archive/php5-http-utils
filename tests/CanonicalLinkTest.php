<?php

require_once dirname(dirname(__file__)) . '/CanonicalLink.php';
require_once dirname(dirname(__file__)) . '/HttpClient.php';
require_once dirname(dirname(__file__)) . '/HttpCache.php';
require_once dirname(dirname(__file__)) . '/HttpRequest.php';
require_once dirname(dirname(__file__)) . '/HttpResponse.php';
require_once dirname(dirname(__file__)) . '/HttpUtils.php';

class CanonicalLinkTest extends PHPUnit_Framework_TestCase {
	var $canon;
	
	public function setUp() {
		$this->canon = new CanonicalLink();
	}

	public function testInit() {
	
	
	}

/****
	public function testNormalUrl() {
		$url = 'http://www.isolani.co.uk/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($url, $canonUrl);
	}
	
	public function testTinyUrl() {
		$url = 'http://tinyurl.com/qyx9uu';
		$endUrl = 'http://www.isolani.co.uk/blog/web/YahooOpenHackLondon2009';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
****/

}


?>