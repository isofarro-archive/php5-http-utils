<?php

/**
* HttpHeaders is an encapsulation of the Http Headers
* of both the HttpRequest and HttpResponse objects.
**/

class HttpHeaders {
	protected $headers;
	protected $headerKey;
	
	public function __construct() {
		$this->headers   = array();
		$this->headerKey = array();
	}
	
	public function addHeader($name, $value=false) {
		if ($value !== false) {
					
		} else {

		}
	}


	public function hasHeader($name) {
		return array_key_exists($name, $this->headerKey);
	}
	
	protected function _addHeader($name, $value) {
			
	
	}
	
	protected function _rebuildHeaderKey() {
		$this->headerKey = array();
		$len = count($this->headers);
		for ($i=0; $i<$len; $i++) {
			
		}
	}
}

/**
* An encapsulation of a single HttpHeader
**/
class HttpHeader {
	var $header;
	var $value;

	public function __construct($name=false, $value=false) {
		if ($name!==false || $value!==false) {
			$this->setHeader($name, $value);
		}
	}

	public function setHeader($name, $value) {
		if ($name!==false) {
			$this->setName($name);
		}
		if ($value!==false) {
			$this->setValue($value);
		}
	}

	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		if (is_string($name)) {
			$this->name = $name;
		}
	}


	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		if (is_string($value)) {
			$this->value = $value;
		}
	}
	
}

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
	
	public function getScheme() {
		return $this->scheme;
	}
	
	public function setScheme($scheme) {
		if (strpos('|http|https|ftp|file|', "|{$scheme}|")!==false) {
			$this->scheme = $scheme;
			$this->_createUrl();
		}
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function setUser($user) {
		if ($user===false || is_null($user)) {
			$this->user = NULL;
			$this->_createUrl();
		} elseif (preg_match('/^[A-Za-z0-9_-]+$/', $user)) {
			$this->user = $user;
			$this->_createUrl();
		}
	}
	
	public function getPass() {
		if ($this->user) {
			return $this->pass;
		}
	}
	
	public function setPass($pass) {
		if ($pass===false || is_null($pass)) {
			$this->pass = NULL;
			$this->_createUrl();
		} elseif (preg_match('/^[^\@]+$/', $pass)) {
			$this->pass = $pass;
			$this->_createUrl();
		}
	}
	
	public function getHost() {
		return $this->host;
	}
	
	public function setHost($host) {
		if (preg_match('/^([A-Za-z0-9-]+\.)+[A-Za-z0-9-]{2,}$/', $host)) {
			$this->host = $host;
			$this->_createUrl();
		}
	}
	
	public function getPort() {
		return $this->port;
	}
	
	public function setPort($port) {
		if ($port===false || is_null($port)) {
			$this->port = NULL;
			$this->_createUrl();
		} elseif (is_numeric($port)) {
			$this->port = $port;
			$this->_createUrl();
		}
	}
	
	public function getPath() {
		return $this->path;
	}

	public function setPath($path) {
		if (preg_match('/^[^?#]+$/', $path)) {
			$this->path = $path;
			$this->_createUrl();
		}
	}

	public function getQuery() {
		return $this->query;
	}
	
	public function getQueryString() {
		return $this->_createQueryString();	
	}
	
	public function setQuery($query) {
		if (is_array($query)) {
			$this->query = $query;
		} elseif ($query) {
			$this->query = array();
			$this->_processQueryString($query);
		}
		$this->_createUrl();
	}

	public function getFragment() {
		return $this->fragment;
	}


	public function setFragment($fragment) {
		if (is_null($fragment)) {
			$this->fragment = NULL;
			$this->_createUrl();
		} else  {
			$this->fragment = $fragment;
			$this->_createUrl();
		}
	}

	public function getUrl() {
		return $this->url;
	}
	
	public function getRawUrl() {
		return $this->rawUrl;
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
		$urlParts = parse_url($url);
		if ($urlParts['path']) {
			if ($urlParts['path'][0]=='/') {
				// An absolute path
				$this->path = $urlParts['path'];
				$this->_createUrl();
			} else {
				// A relative path
				$basename = basename($this->path);
				$path = dirname($this->path); // . '/';
				if ($basename) {
					$path .= '/';
				}				
				$this->path = $path . $urlParts['path'];
				$this->_createUrl();
			}
		}
	}
	
	
	
	public function addQuery($query, $value=false) {
		if (is_array($query)) {
			$this->query = array_merge(
				$this->query,
				$query
			);
		} elseif(is_string($query)) {
			if ($value!==false) {
				$this->query[$query] = $value;
			} else {
				$this->_processQueryString($query);
			}
		}
		$this->_createUrl();
	}
	
	

	protected function _updateUrlParts($urlParts) {
		//print_r($urlParts);

		if (!empty($urlParts['scheme'])) {
			// This is a fully qualified URL
			$this->scheme   = $urlParts['scheme'];
			$this->user     = empty($urlParts['user'])?'':$urlParts['user'];
			$this->pass     = empty($urlParts['pass'])?'':$urlParts['pass'];
			$this->host     = $urlParts['host'];
			$this->port     = empty($urlParts['port'])?'':$urlParts['port'];
		}
		$this->path     = $urlParts['path'];
		$this->fragment = empty($urlParts['fragment'])?'':$urlParts['fragment'];
		
		if (!empty($urlParts['query'])) {
			$this->setQuery($urlParts['query']);
		}
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
		
		if (!empty($this->query)) {
			$queryString = $this->_createQueryString();
			if ($queryString) {
				$parts[] = '?';
				$parts[] = $queryString;
			}
		}
		
		if ($this->fragment) {
			$parts[] = '#' . $this->fragment;
		}
		
		$this->url = implode('', $parts);
	}
	
	protected function _processQueryString($query) {
		$parts = explode('&', $query);
		foreach($parts as $part) {
			//echo "QS-Part:{$part}\n";
			if (strpos($part, '=')!==false) {
				list($key, $value) = explode('=', $part, 2);
				$this->query[$key] = $value;
			} else {
				//echo "ERROR:{$part} Doesn't contain =\n";
				$this->query[$part] = '';
			}
		}
	}
	
	protected function _createQueryString() {
		$queryString = '';
		if (!empty($this->query)) {
			$query = array();
			$isSingle = (count($this->query)==1);
			foreach($this->query as $key=>$value) {
				if ($key) {
					if ($value) {
						$query[] = "$key=$value";
					} else {
						$query[] = $key;
						if (!$isSingle) {
							$query[] = '=';
						}
					}
				}
			}
			$queryString = implode('&', $query);
		}
		return $queryString;
	}
	
}

?>