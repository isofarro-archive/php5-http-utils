<?php

/**
	CanonicalLink finds the canonical link for any url. Keeps track
	of link shorteners, and iframe/frames based toolbar (e.g. Digg)
**/
class CanonicalLink {
	var $config = array(
		'dataDir'  => 'canonical-link',
		'dataFile' => 'links.ser'
	);
	var $cache;
	var $http;
	
	var $lookup = array();
	var $filePath;

	public function __construct($config=false) {
		if ($config) {
			$this->setConfig($config);
		}
		$this->_init();
	}
	
	public function setConfig($config) {
		if (is_array($config)) {
			$this->config = array_merge( $this->config, $config );
		}
		//$this->config = $config;
	}

	public function getCanonicalLink($url) {
		if (empty($this->lookup)) {
			$this->_loadLookup();
		}
		
		if (!empty($this->lookup[$url])) {
			echo '!';
			return $this->lookup[$url];
		}
		
		$newUrl   = $url;
		$response = $this->_getUrl($url);
		$status = $response->getStatus();
		
		if ($status==301 || $status==302) {
			// Redirection taking place
			//echo "Response: "; print_r($response);
			$location = $response->getHeader('Location');
			//echo "Response redirecting to: {$location}\n";
			
			// TODO: Keep following the redirection until we arrive at
			//			a non-redirecting page
			
			$newUrl = $location;
		} else {
			// No redirection
			// TODO: Check for iframe Digg-toolbar like markup
		}
		
		// Cache any canonical references found
		if ($newUrl !== $url) {
			$this->lookup[$url] = $newUrl;
			$this->_saveLookup();
		}
		
		// Default: return starting url
		return $newUrl;
	}
	

	protected function _getUrl($url) {
		if (!$this->http) {
			$this->http = new HttpClient();
		}
		
		$request = new HttpRequest($url);
		$response = $this->http->doRequest($request);
		return $response;
	}	
	
	protected function _init() {
		$path = HttpUtilsConstants::initDataDir($this->config['dataDir']);
		if ($path) {
			echo "Path: {$path}\n";
			$this->filePath = $path . $this->config['dataFile'];
		} else {
			exit("Can't initialise data directory: {$this->config['dataDir']}\n");
		}
	}
	
	protected function _loadLookup() {
		if (file_exists($this->filePath)) {
			$ser = file_get_contents($this->filePath);
			if (strlen($ser)>0) {
				$this->lookup = unserialize($ser);
				return true;
			}
		}
		return false;
	}
	
	protected function _saveLookup() {
		if (count($this->lookup)>0) {
			echo "Saving lookup\n";
			$ser = serialize($this->lookup);
			if (strlen($ser)>0) {
				$res = file_put_contents($this->filePath, $ser);
				return ($res!==false);
			}
		}
		return false;
	}
	
	
}

?>