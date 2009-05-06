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
	
	public function getHeader($name) {
		$pos = $this->_getHeaderPos($name);
		if (!is_null($pos)) {
			return $this->headers[$pos][1];
		}
		return NULL;	
	}
	
	public function getHeaders() {
		return $this->headers;
	}
	
	public function setHeader($name, $value) {
		$pos = $this->_getHeaderPos($name);
		if (!is_null($pos)) {
			$this->headers[$pos] = array($name, $value);
		} else {
			$this->_addHeader($name, $value);
		}
	}
	
	public function removeHeader($name) {
		$pos = $this->_getHeaderPos($name);
		if (!is_null($pos)) {
			unset($this->headers[$pos]);
			$this->_rebuildHeaderKey();
			return true;
		}
		return false;	
	}

	public function hasHeader($name) {
		return array_key_exists($name, $this->headerKey);
	}
	
	protected function _getHeaderPos($name) {
		if ($this->hasHeader($name)) {
			return $this->headerKey[$name];
		}
	}
	
	protected function _addHeader($name, $value) {
		$this->headers[] = array($name, $value);
		$this->_rebuildHeaderKey();
	}
	
	protected function _rebuildHeaderKey() {
		//echo "Rebuild: "; print_r($this->headers);
		$this->headerKey = array();
		$len = count($this->headers);
		while(list($key, $value) = each($this->headers)) {
			$this->headerKey[$value[0]] = $key;
		}
		//print_r($this->headerKey); print_r($this->headers);
	}
}

/**
* An encapsulation of a single HttpHeader
**/
class HttpHeader {
	var $name;
	var $value;

	public function __construct($name=false, $value=false) {
		if ($name!==false || $value!==false) {
			$this->setHeader($name, $value);
		}
	}
	
	public function getHeader() {
		if (is_string($this->value)) {
			return "{$this->name}: {$this->value}";	
		} elseif (is_array($this->value)) {
			return $this->name . ': ' . implode(',', $this->value);
		}
	}
	
	public function getHeaderArray() {
		return array($this->name, $this->value);
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
			// TODO: Check if it contains q-values
			$this->value = $value;
		} elseif (is_array($value)) {
			$this->value = $value;
		}
	}
	
}

/**
* An encapsulation of a qvalue suffixed Header item
**/
class HttpHeaderQvalue {
	protected $value;
	protected $qvalue;
	
	public function __construct($value=false, $qvalue=false) {
		if ($value) {
			$this->setValue($value, $qvalue);
		}
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value, $qvalue=false) {
		$this->value = $value;
		if ($qvalue) {
			$this->setQvalue($qvalue);
		}
	}

	public function getQvalue() {
		return $this->qvalue;
	}

	public function setQvalue($qvalue) {
		$this->qvalue = $qvalue;
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