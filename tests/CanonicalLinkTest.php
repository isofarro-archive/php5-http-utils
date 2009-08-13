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
		* http://ow.ly/ddvW
		* http://twurl.nl/26tc7d
		* http://migre.me/207l
		* http://tumblr.com/xcg1zx6jw





		URL shorteners:
* http://2tu.us/mbi
* http://ad.vu/ius7
												* http://bit.ly/bD5sm
* http://budurl.com/usid9
												* http://cli.gs/MPt1t
* http://chilp.it/?de477f
* http://digg.com/d3z0gC?t
												* http://ff.im/5QPgf
												* http://is.gd/1R6Xd
* http://kl.am/1LMT
* http://migre.me/4Hv7
* http://ow.ly/15J4VF
												* http://ping.fm/Oty04
* http://shar.es/z1G3
* http://short.to/lcj3
* http://shortna.me/26797
												* http://snipr.com/o4zii
* http://su.pr/28KrB9
* http://tcrn.ch/1V4Q
* http://tiny.cc/ZcH0D
												* http://tinyurl.com/n3t4h6
												* http://tr.im/utgz
* http://tumblr.com/xac2il097
* http://TwitPWR.com/mP0/
												* http://twitthis.com/qiaex4
												* http://twitzap.com/u/VmA
												* http://twurl.nl/3xv6kx
												* http://url.ie/25u7
												* http://url4.eu/9aZb
												* http://zz.gd/396381

		
	**/

	public function testNormalUrl() {
		$url = 'http://www.isolani.co.uk/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($url, $canonUrl);
	}

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
	
	public function testIsSniprUrl() {
		$url      = 'http://snipr.com/o4zii';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
	
	public function testIsTrImUrl() {
		$url      = 'http://tr.im/vl2O';
		$endUrl   = 'http://webaim.org/projects/steppingstones/cognitiveresearch';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
	

	public function testIsSnurlUrl() {
		$url      = 'http://snurl.com/olpjb';
		$endUrl   = 'http://www.stc-access.org/2009/08/01/a-whole-lotta-html5-love/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	
	public function testIsCliGsUrl() {
		$url      = 'http://cli.gs/MPt1t';
		$endUrl   = 'http://blog.gingertech.net/2009/07/29/first-experiments-with-itext/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsFfImUrl() {
		$url      = 'http://ff.im/5QPgf';
		$endUrl   = 'http://friendfeed.com/scobleizer/49428cdf/why-and-are-full-of-it-about-twitter-vs';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsZzGdUrl() {
		// 302 Multiple redirects
		$url      = 'http://zz.gd/396381';
		$endUrl   = 'http://htmlcssjavascript.com/html/sometimes-dreamweaver-surprises-me-great-accessibility-enhancement/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsUrl4EuUrl() {
		// 302 Redirects
		$url      = 'http://url4.eu/9aZb';
		$endUrl   = 'http://rss2twitter.com';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsUrlIeUrl() {
		$url      = 'http://url.ie/25u7';
		$endUrl   = 'http://blog.gingertech.net/2009/08/03/aspects-of-video-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsTwurlNlUrl() {
		$url      = 'http://twurl.nl/3xv6kx';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsTwitzapUrl() {
		$url      = 'http://twitzap.com/u/VmA';
		$endUrl   = 'http://www.bbc.co.uk/ouch/messageboards/F2322273?thread=6790842&latest=1#p83495761';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsTwitthisUrl() {
		$url      = 'http://twitthis.com/qiaex4';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}


/****
	public function testIsUrl() {
		$url      = '';
		$endUrl   = '';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
****/

	
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