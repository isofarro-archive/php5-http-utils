<?php

class HttpRequest {
	protected $method;
	protected $path;
	protected $version = 'HTTP/1.1';
	
	protected $headers = array();
	protected $body;
	
	// Raw data
	protected $url;

	public function __construct($url = false) {
		if ($url) {
			$this->setUrl($url);
		}
	}
	
	public function setUrl($url) {
		$urlParts = parse_url($url);
		
	}
	
	
}


?>