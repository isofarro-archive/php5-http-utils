<?php

class HttpRequest {
	protected $method  = 'GET'; 
	//protected $path;	   // Path segment, without query string (mandatory)
	//protected $query;    // Query string (optional)
	protected $version = 'HTTP/1.1';
	
	protected $headers = array();
	protected $body;
	
	// Raw data
	protected $url;


	public function __construct($url = false) {
		$this->url = new HttpUrl();
		if ($url) {
			$this->setUrl($url);
		}
	}
	
	public function setUrl($url) {
		$this->url->setUrl($url);
	}
	
	public function getUrl() {
		return $this->url->getUrl();
	}
	
	public function setRelativeUrl($url) {
		$this->url->setRelativeUrl($url);
	}
	
	public function getPath() {
		if ($this->url) {
			return $this->url->getPath();
		}
	}
	
	public function setPath($path) {
		if ($this->url) {
			$this->url->setPath($path);
		}
	}

	public function getQuery() {
		if ($this->url) {
			return $this->url->getQuery();
		}
	}
	
	public function getQueryString() {
		if ($this->url) {
			return $this->url->getQueryString();
		}
	}
	
	public function setQuery($query) {
		if ($this->url) {
			$this->url->setQuery($query);
		}
	}

	public function getHost() {
		if ($this->url) {
			return $this->url->getHost();
		}
	}
	
	public function setHost($host) {
		if ($this->url) {
			$this->url->setHost($host);
		}
	}


}


?>