<?php

/**
* HttpUrl an encapsulation of a HTTP URL, to ease the correct
* processing on URLs in HTTP requests and responses.
**/
class HttpUrl {
	protected $scheme;
	protected $user;
	protected $pass;
	protected $host;
	protected $port;
	protected $path;
	protected $query;
	protected $fragment;
	
	protected $url;
	protected $rawUrl;
	
	public function __construct($url=false) {
		if ($url) {
			$this->setUrl($url);
		}
	}
	
	public function setUrl($url) {
		$urlParts = parse_url($url);
		if ($urlParts) {
			$this->_updateUrlParts($urlParts);
			$this->_createUrl();
			$this->rawUrl = $url;
		}		
	}
	
	public function setRelativeUrl($url) {

	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getRawUrl() {
		return $this->rawUrl;
	}
	

	protected function _updateUrlParts($urlParts) {
		//print_r($urlParts);

		if ($urlParts['scheme']) {
			// This is a fully qualified URL
			$this->scheme   = $urlParts['scheme'];
			$this->user     = $urlParts['user'];
			$this->pass     = $urlParts['pass'];
			$this->host     = $urlParts['host'];
			$this->port     = $urlParts['port'];
		}
		$this->path     = $urlParts['path'];
		$this->query    = $urlParts['query'];
		$this->fragment = $urlParts['fragment'];
	}

	protected function _createUrl() {
		$parts = array($this->scheme, '://');
		
		if ($this->user) {
			$parts[] = $this->user;
			if ($this->pass) {
				$parts[] = ':' . $this->pass;
			}
			$parts[] = '@';
		}
		
		$parts[] = $this->host;
		
		if ($this->port) {
			$parts[] = ':' . $this->port;
		}
		
		$parts[] = $this->path;
		
		if ($this->query) {
			$parts[] = '?' . $this->query;
		}
		
		if ($this->fragment) {
			$parts[] = '#' . $this->fragment;
		}
		
		$this->url = implode('', $parts);
	}
}

?>