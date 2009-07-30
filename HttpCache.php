<?php

class HttpCache {
	##protected $rootDir = '/tmp/http-cache/';
	protected $rootDir = '/var/cache/isolani/http-cache/';
	protected $altRootDir = '/tmp/http-cache/';

	public function __construct($dir=false) {
		if ($dir) {
			$this->setRootDir($dir);
		} else {
			$this->_initDefaultCacheDir();
		}
	}

	public function setRootDir($dir) {
		if (file_exists($dir) && is_dir($dir)) {
			$this->rootDir = $dir;
		}
	}
	
	public function getRootDir() {
		return $this->rootDir;
	}


	public function cache($url, $text) {
		if (!is_string($text) && (strlen($text)>0) ) {
			return NULL;
		}
		
		$file = $this->_getFileName($url);
		
		//echo "Url: {$url}\nFile: {$file}\n";
		$ret = file_put_contents($file, $text);
		return (bool)$ret;
	}
	
	public function uncache($url) {
		$file = $this->_getFileName($url);
		if (file_exists($file)) {
			return unlink($file);
		}		
		return false;
	}
	
	public function isCached($url) {
		$file = $this->_getFileName($url);
		//echo "Url: {$url}\nFile: {$file}\n";
		return file_exists($file);
	}
	
	public function get($url) {
		$file = $this->_getFileName($url);
		if (file_exists($file)) {
			$body = file_get_contents($file);
			if (strlen($body>0)) { 
				return $body;
			} else {
				// Delete empty cached entries
				$this->uncache($url);
			}
		}
		return NULL;
	}
	
	public function isFresh($url, $expire=false) {
	
	}	


	protected function _getFileName($url) {
		$domain = parse_url($url, PHP_URL_HOST);
		$key      = md5($url);

		$filePath = $this->_getFullPath($domain) . $key;	
		return $filePath;
	}

	protected function _getFullPath($domain) {
		if ($domain === false) {
			$domainDir = $this->rootDir;
		} else {
			$domainDir = $this->rootDir . $domain . '/';
			if (!$this->_initDomainDir($domainDir)) {
				return NULL;
			}
		}
		return $domainDir;
	}

	protected function _initDomainDir($domainDir) {
		if (!file_exists($domainDir)) {
			if (!@mkdir($domainDir)) {
				echo "ERROR: Couldn't create $domainDir\n";
				return false;
			}				
		}
		return true;
	}

	protected function _initDefaultCacheDir() {
		if (file_exists($this->rootDir)) {
			return;
		}

		if (!file_exists($this->altRootDir)) {
			$ret = mkdir($this->altRootDir, 0777, true);
			if (!$ret) {
				echo "ERROR: Can't create a cache at {$this->altRootDir}\n";
			}
		}
		$this->rootDir = $this->altRootDir;
	}

}

?>
