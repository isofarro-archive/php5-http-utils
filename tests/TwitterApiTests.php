<?php

require_once '../TwitterApi.php';

class TwitterApiTests extends PHPUnit_Framework_TestCase {
	var $twitter;

	public function setUp() {
		$this->twitter = new TwitterApi();
	}
	
	public function tearDown() {
	
	}
	
	public function testInitTwitterApi() {
		$this->assertNotNull($this->twitter);

	}	
	
	public function testGetRequestToken() {
		$token = $this->twitter->getRequestToken();
		$this->assertNotNull($token);
		$this->assertTrue(is_string($token));
	}

}


?>