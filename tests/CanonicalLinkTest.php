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
		* http://su.pr/28KrB9 -- StumbleUpon
		* http://ad.vu/ius7
		* http://digg.com/educational/The_American_Textbook_Accessibility_Act_Anyone
	**/

/****
	public function testIsUrl() {
		$url      = '';
		$endUrl   = '';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
****/


//****
	public function testNormalUrl() {
		$url = 'http://www.isolani.co.uk/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($url, $canonUrl);
	}

	public function testTinyurlUrl() {
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


	public function testIsTwitPwrUrl() {
		$url      = 'http://TwitPWR.com/mP0/';
		$endUrl   = 'http://www.merttol.com/articles/web/find-out-about-the-accessibility-standards.html';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsTumblrUrl() {
		$url      = 'http://tumblr.com/xac2il097';
		$endUrl   = 'http://koikoo.tumblr.com/post/152145547/twitter-hopes-to-improve-user-accessibility-with';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsTinyCcUrl() {
		$url      = 'http://tiny.cc/ZcH0D';
		$endUrl   = 'https://secure2.convio.net/appf/site/SPageServer?pagename=Petition_to_HIT_PC&JServSessionIdr011=ob80gcjlw6.app2b';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsTcrnChUrl() {
		$url      = 'http://tcrn.ch/1V4Q';
		$endUrl   = 'http://www.techcrunch.com/2009/07/29/microsoft-yahoo-search-deal-the-official-press-release/?awesm=tcrn.ch_1V4Q&utm_campaign=techcrunch&utm_medium=tcrn.ch-copypaste&utm_source=direct-tcrn.ch&utm_content=shorturl';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsSuPrUrl() {
		// Stumbleupon frameset
		$url      = 'http://su.pr/28KrB9';
		//$endUrl   = 'http://www.stumbleupon.com/s/#28KrB9/www.uxbooth.com/blog/5-tools-to-increase-accessibility//';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsShortnaMeUrl() {
		$url      = 'http://shortna.me/26797';
		$endUrl   = 'http://www.washingtonpost.com/wp-dyn/content/article/2009/08/05/AR2009080504065.html?hpid=moreheadlines&FORM=ZZNR';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsShortToUrl() {
		$url      = 'http://short.to/lcj3';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsUrl() {
		$url      = 'http://shar.es/z1G3';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsSharEsUrl() {
		$url      = 'http://shar.es/z1G3';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsMigreMeUrl() {
		$url      = 'http://migre.me/4Hv7';
		$endUrl   = 'http://juicystudio.com/article/requiring-alt-attribute-html5.php';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsKlAmUrl() {
		$url      = 'http://kl.am/1LMT';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIsChilpItUrl() {
		$url      = 'http://chilp.it/?de477f';
		$endUrl   = 'http://www.uxbooth.com/blog/5-tools-to-increase-accessibility/';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
	
	public function testIsBudurlUrl() {
		$url      = 'http://budurl.com/usid9';
		$endUrl   = 'http://hci-hyderabad.org/usid2009/index.htm';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}

	public function testIs2tuUsUrl() {
		$url      = 'http://2tu.us/mbi';
		$endUrl   = 'http://cacm.acm.org/magazines/2009/8/34496-a-blind-persons-interactions-with-technology/fulltext';
		$canonUrl = $this->canon->getCanonicalLink($url);
		$this->assertEquals($endUrl, $canonUrl);
	}
//****/


	
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