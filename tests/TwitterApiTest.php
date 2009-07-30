<?php

require_once dirname(dirname(__file__)) . '/TwitterApi.php';
require_once dirname(dirname(__file__)) . '/HttpUtils.php';
require_once dirname(dirname(__file__)) . '/HttpCache.php';
require_once dirname(dirname(__file__)) . '/HttpRequest.php';
require_once dirname(dirname(__file__)) . '/HttpResponse.php';
require_once dirname(dirname(__file__)) . '/HttpClient.php';

class TwitterApiTest extends PHPUnit_Framework_TestCase {
	var $twitter;

	public function setUp() {
		$this->twitter = new TwitterApi();
	}
	
	public function tearDown() {
	
	}

/****
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
****/


/****
	public function testGetPublicTimeline() {
		$timeline = $this->twitter->getPublicTimeline();
		
		//print_r($timeline);
		$this->assertNotNull($timeline);
		$this->assertType('array', $timeline);
		$this->assertEquals(20, count($timeline));
		
		//print_r($timeline[0]);
		$tweet = $timeline[0];

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
		$this->assertNotNull($tweet->user->joined);
		
	}


	public function testGetUserTimeline() {
		$username = 'isofarro_public';
		$timeline = $this->twitter->getUserTimeline($username);
		
		//print_r($timeline);
		$this->assertNotNull($timeline);
		$this->assertType('array', $timeline);

		$tweet = $timeline[0];

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
		$this->assertNotNull($tweet->user->joined);
		
	}


	public function testGetProtectedUserTimeline() {
		$username = 'isofarro';
		$timeline = $this->twitter->getUserTimeline($username);
		
		$this->assertNull($timeline);
	}
****/

	public function testSearchQuery() {
		$query = 'accessibility OR a11y';
		
		$results = $this->twitter->search($query);
		//print_r($results);
		$this->assertNotNull($results);
		$this->assertEquals(100, count($results));
	}

	public function testSearchAll() {
		$query = 'accessibility OR a11y';
		
		$results = $this->twitter->searchAll($query);
		//print_r($results);
		$this->assertNotNull($results);
		$this->assertType('array', $results);
		$this->assertType('object', $results[0]);
		$this->assertTrue(count($results)>100);
	}

/****
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
****/

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
