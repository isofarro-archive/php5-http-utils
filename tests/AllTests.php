<?php

require_once 'HttpHeaderTest.php';
require_once 'HttpHeadersTest.php';
require_once 'HttpRequestTest.php';
require_once 'HttpResponseTest.php';
require_once 'HttpUrlTest.php';
require_once 'HttpClientTest.php';
require_once 'TwitterApiTest.php';

class AllTests {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('HttpUtils');

		$suite->addTestSuite('HttpHeadersTest');
		$suite->addTestSuite('HttpHeaderTest');
		$suite->addTestSuite('HttpRequestTest');
		$suite->addTestSuite('HttpResponseTest');
		$suite->addTestSuite('HttpUrlTest');
		//$suite->addTestSuite('HttpClientTest');
		//$suite->addTestSuite('TwitterApiTest');
		return $suite; 
	}

	protected function setUp() {
		print "AllTests::setUp()\n";
	}

	protected function tearDown() {
		print "AllTests::tearDown()\n";
	}
}

?>
