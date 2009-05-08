<?php

require_once dirname(dirname(__file__)) . '/WeFollowApi.php';
require_once dirname(dirname(__file__)) . '/HtmlParser.php';

class WeFollowApiTest extends PHPUnit_Framework_TestCase {
	var $api;

	public function setUp() {
		$this->api = new WeFollowApi();
	}
	
	public function tearDown() {
	
	}
	
	public function testGetPeople() {
		$file = '/home/user/Documents/savedPages/wefollow-accessibility.html';
		$people = $this->api->getTaggedPeople($file);
		
		$this->assertNotNull($people);
		$this->assertType('array', $people);
	}
}

?>
