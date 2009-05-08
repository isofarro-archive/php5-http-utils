<?php

class WeFollowApi {
	var $http;
	var $parser;
	
	var $tagBaseUrl = 'http://wefollow.com/tags/';

	// Iterator methods
	var $nextUrl;
	var $prevUrl;
	
	public function getTaggedPeople($tag) {
		$html = $this->_getRawData($tag); 
		echo "TaggedPeople: "; print_r($html);
		$pageData = $this->_scrapeTagPage($html);
	}
	
	
	
	protected function _scrapeTagPage($html) {
		if (empty($this->parser)) {
			$this->_initParser();
		}
	}	
	
	
	protected function _getRawData($tag) {
		$this->nextUrl = NULL;
		$this->prevUrl = NULL;
		if (preg_match('/^\w+$/', $tag)) {
			echo "It's a URL\n";
			return $this->_getTagPage($tag);
		} elseif (file_exists($tag)) {
			echo "It's a file";
			return file_get_contents($tag);
		} else {
			echo "ERROR: Cannot determine {$tag}\n";
		}
	}
	
	protected function _getTagPage($tag) {
		$url = $this->tagBaseUrl . $tag;
		return $this->_getUrl($url);
	}

	protected function _getUrl($url) {
		if (empty($this->http)) {
			$this->http = new HttpClient();
		}
		return $this->http->getUrl($url);
	}
	
	protected function _initParser() {
	
	}
}

?>