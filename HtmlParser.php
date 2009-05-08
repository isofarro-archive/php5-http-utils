<?php

require_once dirname(__file__) . '/simplehtmldom/simple_html_dom.php';

/**
* A simple wrapper around simplehtmldom
**/
class HtmlParser {

	public function parseHtml($html) {
		return str_get_html($html);
	}

}

?>