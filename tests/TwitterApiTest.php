<?php

require_once dirname(dirname(__file__)) . '/TwitterApi.php';
require_once dirname(dirname(__file__)) . '/HttpClient.php';
require_once dirname(dirname(__file__)) . '/HttpCache.php';
require_once dirname(dirname(__file__)) . '/HttpRequest.php';
require_once dirname(dirname(__file__)) . '/HttpResponse.php';
require_once dirname(dirname(__file__)) . '/HttpUtils.php';

class TwitterApiTest extends PHPUnit_Framework_TestCase {
	var $twitter;

	public function setUp() {
		$this->twitter = new TwitterApi();
	}
	
	public function tearDown() {
	
	}

	public function testInitTwitterApi() {
		$this->assertNotNull($this->twitter);

	}

	public function testRateLimit() {
		$limit = $this->twitter->getRateLimit();
		$this->assertType('integer', $limit);
	}
	
	public function testHasRequests() {
		$limit = $this->twitter->getRateLimit();
		if ($limit > 0) {
			$this->assertTrue($this->twitter->hasRequests());
		} else {
			$this->assertFalse($this->twitter->hasRequests());
		}
	}
	
	public function testRateLimitStatus() {
		$limit = $this->twitter->getRateLimitStatus();

		$this->assertNotNull($limit);
		$this->assertNotNull($limit->hourly_limit);
		$this->assertNotNull($limit->reset_time);
		$this->assertNotNull($limit->reset_time_in_seconds);
		$this->assertNotNull($limit->remaining_hits);
	}
	
	public function testGetPublicTimeline() {
		$public = $this->twitter->getPublicTimeline();
		
		//print_r($public);
		$this->assertNotNull($public);
		$this->assertType('array', $public);
		$this->assertEquals(20, count($public));
		
		//print_r($public[0]);
		$tweet = $public[0];

		$this->assertNotNull($tweet->id);
		$this->assertNotNull($tweet->created);
		$this->assertNotNull($tweet->text);

		$this->assertNotNull($tweet->user);
		$this->assertNotNull($tweet->user->id);
		$this->assertNotNull($tweet->user->username);
		$this->assertNotNull($tweet->user->fullname);
		$this->assertNotNull($tweet->user->image);
		$this->assertNotNull($tweet->user->followers);
		$this->assertNotNull($tweet->user->friends);
		
	}



	public function testGetFriends() {
		//$username = 'isofarro_public';
		$username = 'AccessibleTwitr';
		$friends = $this->twitter->getFriends($username);
	
		//print_r($friends);
		if ($this->twitter->hasRequests()) {
			$this->assertNotNull($friends);
			$this->assertType('array', $friends);
			//$this->assertTrue(count($friends)>130);
		} else {
			// Sometimes we can get a cached response - which is good
			// TODO: Try to do this test without caching
			//$this->assertNull($friends);
		}
	}


/****
	// This test is designed to hit the Twitter API limits, so use sparingly
	public function testRateLimitedGetFriends() {
		$usernames = array(  
			'tbabinszki',	'WebBizCEO', 	'yenra',		'blkdykegoddess',
			'cookiecrook',	'steno',			'afhill',	'pixeldiva'
		);

		foreach ($usernames as $username) {
			//echo "\n{$username}: ";
			$friends = $this->twitter->getFriends($username);
	
			//print_r($friends);
			if (is_null($friends)) {
				echo 'N';
				$this->assertFalse($this->twitter->hasRequests());
			} else {
				$this->assertNotNull($friends);
				$this->assertType('array', $friends);
				$this->assertTrue(count($friends)>0);
			}

		}
	}
****/

/****	
	public function testGetRequestToken() {
		$token = $this->twitter->getRequestToken();
		$this->assertNotNull($token);
		$this->assertTrue(is_string($token));
	}
****/

}


?>
