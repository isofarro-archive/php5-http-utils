<?php

require_once dirname(dirname(__file__)) . '/HtmlParser.php';

class HtmlParserTest extends PHPUnit_Framework_TestCase {
	var $parser;
	
	public function setUp() {
		$this->parser = new HtmlParser();
	}

	public function testParseHtml() {
		$html = <<<HTML
<html>
<head>
	<title>Hello World</title>
</head>
<body>
	<h1>This is an H1 title</h1>
</body>
</html>
HTML;
		$dom = $this->parser->parseHtml($html);

		$this->assertNotNull($dom);
		$this->assertNotNull($dom->root);
		$this->assertNotNull($dom->root->tag);
		$this->assertNotNull($dom->root->children);
		$this->assertNotNull($dom->root->children[0]);
		$this->assertNotNull($dom->root->children[0]->tag);
		$this->assertEquals('root', $dom->root->tag);
		$this->assertEquals('html', $dom->root->children[0]->tag);
	}

}

?>
