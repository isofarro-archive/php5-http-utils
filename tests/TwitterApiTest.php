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

	public function testGetFriends() {
		$username = 'isofarro_public';
		$friends = $this->twitter->getFriends($username);
	
		//print_r($friends);
		$this->assertNotNull($friends);
		$this->assertType('array', $friends);
		$this->assertTrue(count($friends)>130);
	}

/****	
	public function testGetRequestToken() {
		$token = $this->twitter->getRequestToken();
		$this->assertNotNull($token);
		$this->assertTrue(is_string($token));
	}
****/

}


?>
