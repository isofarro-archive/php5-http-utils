<?php

class CanonicalLink {
	var $config;
	var $cache;
	var $http;

	public function __construct($config=false) {
		if ($config) {
			$this->setConfig($config);
		}
	}
	
	public function setConfig($config) {
		$this->config = $config;
		$this->_init();
	}

	public function getCanonicalLink($url) {
		return $url;
	}
	
	protected function _init() {
	
	}
}

?>