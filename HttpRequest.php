<?php

class HttpRequest {
	protected $method  = 'GET'; 
	//protected $path;  // Path segment, without query string (mandatory)
	//protected $query; // Query string (optional)
	protected $version = 'HTTP/1.1';
	
	protected $headers;
	protected $body;
	
	// Raw data
	protected $url;
	
	protected $userAgent = 
		'HttpClient 0.0.1 - http://github.com/isofarro/php5-http-utils';



	public function __construct($url = false) {
		$this->url = new HttpUrl();
		if ($url) {
			$this->setUrl($url);
		}
		$this->headers = new HttpHeaders();
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


	public function getMethod() {
		return $this->method;
	}
	
	public function setMethod($method) {
		$method = strtoupper($method);
		if (strpos('|GET|POST|PUT|DELETE|HEAD|', '|'.$method.'|')!==false) {
			$this->method = $method;
		}
	}

	public function getVersion() {
		return $this->version;
	}
	
	public function setVerson($version) {
		$version = strtoupper($version);
		if (preg_match('/^HTTP\/\d\.\d+/', $version)) {
			$this->method = $method;
		}
	}


	public function getBody() {
		return $this->body;
	}
	
	public function setBody($body) {
		$this->body = $body;
	}
	
	public function getHeaders() {
		$headers = $this->headers->getHeaders();
		$headerList = array();
		foreach($headers as $header) {
			$headerList[] = "{$header[0]}: {$header[1]}";
		}
		return $headerList;
	}
	
	
	public function _initHttpHeaders() {
		$host = $this->getHost();
		if ($host) {
			$this->headers->setHeader('Host', $host);
		}
		
		if (!$this->headers->hasHeader('User-Agent')) {
			$this->headers->setHeader('User-Agent', $this->userAgent);
		}
	}
}


?>