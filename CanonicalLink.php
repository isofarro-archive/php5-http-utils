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
		echo "Path: {$path}\n";
	}
}

?>