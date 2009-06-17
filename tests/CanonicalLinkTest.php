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
	
	/**
		Frame-based tinyurls:

	
	
	
	**/

/****
	public function testNormalUrl() {
		$url = 'http://www.isolani.co.uk/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($url, $canonUrl);
	}
****/
	
	public function testTinyUrl() {
		$url      = 'http://tinyurl.com/qyx9uu';
		$endUrl   = 'http://www.isolani.co.uk/blog/web/YahooOpenHackLondon2009';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
	
	public function testPingFmUrl() {
		$url      = 'http://ping.fm/dAqfu';
		$endUrl   = 'http://www.selectbooks.com/t_disabilityland.html';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testBitLyUrl() {
		$url      = 'http://bit.ly/EbnV4';
		$endUrl   = 'http://apiwiki.twitter.com/Streaming-API-Documentation';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsGdUrl() {
		$url      = 'http://is.gd/12zij';
		$endUrl   = 'http://www.flickr.com/photos/formfromfunction/170650901/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

/****
	public function testMultipleRedirectUrl() {
		$url      = 'http://ping.fm/lzYNA';
		$endUrl   = 'http://web.me.com/abrightman/DisabilityLand/About.html';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
****/

}


?>