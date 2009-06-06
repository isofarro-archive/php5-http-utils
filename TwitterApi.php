<?php

// TODO Cache all GET requests heavily

class TwitterApi {
	var $searchBase  = 'http://search.twitter.com/';
	var $twitterBase = 'http://twitter.com/';
	
	var $format = 'json';

	var $http;
	
	// Total number of requests left
	var $requestLimit = -1;
	var $hitLimit     = false;
	
	// How much each service requests costs towards the rate limit
	var $serviceCost = array(
		'account/rate_limit_status' => 0,
		'statuses/friends'          => 1
	);

	public function getRequestToken() {
		return 'Hello world';
	}

	/**
		getRateLimit - returns the number of requests we have left
	**/	
	public function getRateLimit() {
		$response = $this->getRateLimitStatus();
		if (!empty($response->remaining_hits)) {
			return $response->remaining_hits;
		}
		return 0;
	}
	
	/**
		hasRequests - returns true if we haven't reached the rate limit.
	**/
	public function hasRequests() {
		if (!$this->hitLimit && $this->requestLimit<=0) {
			$this->requestLimit = $this->getRateLimit();
			if ($this->requestLimit == 0) {
				$this->hitLimit = true;
			}
		}
		return ($this->requestLimit > 0);
	}


	###
	### Account services
	###
	
	public function getRateLimitStatus() {
		$service = 'account/rate_limit_status';
		$response = $this->_doTwitterApiRequest($service, NULL, false);
		return $response;
	}

	
	###
	### Status services
	###

	public function getFriends($user) {
		$service = 'statuses/friends';
		$friends = array();
		
		$response = $this->_doTwitterApiRequest(
			$service, 
			array('id' => $user)
		);
		//echo "Received response: "; print_r($response);

		if (is_null($response)) {
			// No response received on first request
			// Check whether its because we have no requests
			if (!$this->hasRequests()) { 
				// We ran out of requests
				return NULL;
			} else {
				// Our user has no friends
				return array();
			}
		}
		$page = 1;
		
		while (count($response)>0) {
			if (!empty($response->error)) {
				echo "ERROR: {$response->error}\n";
				break;
			}
			
			$batch = $this->_formatFriends($response);
			$friends = array_merge($friends, $batch);
			$page++;

			$response = $this->_doTwitterApiRequest(
				'statuses/friends', 
				array(
					'id'   => $user,
					'page' => $page
				)
			);

			// Run out of requests, so return NULL
			if (!$this->hasRequests() && is_null($response)) {
				return NULL;
			}
		}
		
		//print_r($friends);
		return $friends;
	}


	##
	## Formatting methods
	##

	protected function _formatFriends($response) {
		$friends = array();
		
		foreach($response as $person) {
			$friend = (object) NULL;
			
			$friend->username    = $person->screen_name;
			$friend->image       = $person->profile_image_url;
			$friend->followers   = $person->followers_count;
			$friend->fullname    = $person->name;
			
			if (!empty($person->description)) {
				$friend->bio         = $person->description;
			}

			if (!empty($person->url)) {
				$friend->website     = $person->url;
			}

			if (!empty($person->status)) {
				$friend->latestTweet = $person->status->text; 
			}
			
			$friends[] = $friend;		
		}

		return $friends;	
	}



	##
	##
	##

	protected function _doTwitterApiRequest($service, $params=NULL, $cache=true) {
		$url      = "{$this->twitterBase}{$service}.{$this->format}";
		
		$serviceCost = $this->serviceCost[$service];

		//echo " [{$serviceCost}<{$this->requestLimit}]";
		if ($service == 'account/rate_limit_status' || $this->hasRequests() || $serviceCost==0 || ($serviceCost <= $this->requestLimit)) {
			$response = $this->_doHttpApiRequest('GET', $url, $params, $cache);
			$this->requestLimit =  $this->requestLimit - $serviceCost;
		} else {
			// Try an offline cache request
			echo '>';
			$response = $this->_doHttpApiRequest('GET', $url, $params, $cache, true);
		}		

		return json_decode($response);
	}
	
	protected function _doHttpApiRequest($method, $url, $params, $cache=true, $offline=false) {
		if ($method=='GET') {
			if (!empty($params)) {
				$query = http_build_query($params);
				$url = "{$url}?{$query}";
			}
			return $this->_getUrl($url, $cache, $offline);
		} else {
			echo "ERROR: unsupported HTTP method: {$method}\n";
			return NULL;
		}
	}
	
	protected function _getUrl($url, $cache=true, $offline=false) {
		if (empty($this->http)) {
			$this->http = new HttpClient();
		}
		if ($offline) {
			return $this->http->getCachedUrl($url);
		} else {
			return $this->http->getUrl($url, $cache);
		}
	}
	
}


?>