<?php

class HttpRequest {
	protected $method  = 'GET'; 
	protected $path;	   // Path segment, without query string (mandatory)
	protected $query;    // Query string (optional)
	protected $version = 'HTTP/1.1';
	
	protected $headers = array();
	protected $body;
	
	// Raw data
	protected $url;
	protected $urlParts;

	public function __construct($url = false) {
		if ($url) {
			$this->setUrl($url);
		}
	}
	
	public function setUrl($url) {
		$this->url = new HttpUrl($url);
	}
	
	public function getUrl() {
		return $this->url->getUrl();
	}

}


?>