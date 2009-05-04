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
		$this->url       = $url;
		$this->urlParts  = parse_url($url);
		$this->_updateUrlParts();
	}
	
	public function getUrl() {
		return $this->url;
	}


	/**
	* _updateUrlParts: updates the various pieces of a request
	* affected by a change of url
	**/
	protected function _updateUrlParts() {
		// TODO: update Path
		// TODO: update Host header
		// TODO: update query string	
	}
	
}


?>