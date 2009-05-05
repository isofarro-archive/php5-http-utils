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
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getRawUrl() {
		return $this->rawUrl;
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
	
	public function getQuery() {
		return $this->query;
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

			$parts[] = '?';
			$parts[] = implode('&', $query);
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
	
}

?>