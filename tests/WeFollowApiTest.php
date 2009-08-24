<?php

require_once dirname(dirname(__file__)) . '/WeFollowApi.php';
require_once dirname(dirname(__file__)) . '/HtmlParser.php';
require_once dirname(dirname(__file__)) . '/HttpClient.php';
require_once dirname(dirname(__file__)) . '/HttpCache.php';
require_once dirname(dirname(__file__)) . '/HttpRequest.php';
require_once dirname(dirname(__file__)) . '/HttpResponse.php';
require_once dirname(dirname(__file__)) . '/HttpUtils.php';

class WeFollowApiTest extends PHPUnit_Framework_TestCase {
	var $api;

	public function setUp() {
		$this->api = new WeFollowApi();
	}
	
	public function tearDown() {
	
	}
	

	public function testGetPeople() {
		//$file = '/home/user/Documents/savedPages/wefollow-accessibility.html';
		//$file = '/home/isofarro/Documents/savedPages/wefollow-accessibility.html';
		$url = 'http://wefollow.com/twitter/accessibility';
		$people = $this->api->getTaggedPeople($url);
		
		$this->assertNotNull($people);
		$this->assertType('array', $people);
		$this->assertEquals(25, count($people));

		// Get first person
		$person = $people[0];
		$this->assertNotNull($person->username);
		$this->assertEquals('tbabinszki', $person->username);
		
		// Check iterable
		$this->assertTrue($this->api->hasNext());
		$this->assertFalse($this->api->hasPrevious());
	}


	public function testIteratePeople() {
		$tag  = 'accessibility';
		$list = array();
		
		$people = $this->api->getTaggedPeople($tag);

		$this->assertNotNull($people);
		$this->assertType('array', $people);
		$this->assertEquals(25, count($people));
		$list = array_merge($list, $people);
	
		// Check iterable
		$this->assertTrue($this->api->hasNext());

		$people = $this->api->next();
		$list = array_merge($list, $people);

		$this->assertNotNull($people);
		$this->assertType('array', $people);
		$this->assertEquals(50, count($list));

		// Check iterable
		$this->assertTrue($this->api->hasNext());
		
		while($this->api->hasNext()) {
			$people = $this->api->next();
			$list = array_merge($list, $people);
		}

		$this->assertTrue(count($list)>50);
		$this->assertEquals(164, count($list));

	}


}

?>
