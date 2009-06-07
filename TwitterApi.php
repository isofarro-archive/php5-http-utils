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
		
		'statuses/public_timeline'  => 1,
		'statuses/friends'          => 1
	);

	public function getRequestToken() {
		return 'Hello world';
	}

	/**
		getRateLimit - returns the number of requests we have left
	**/	
	public function getRateLimit() {
		if (!$this->hitLimit) {
			$response = $this->getRateLimitStatus();
			if (!empty($response->remaining_hits)) {
				return $response->remaining_hits;
			}
			// Request failed. probably off line
			$this->hitLimit = true;
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

	/**
		getFriends - returns an array of people the specified user is following.
		This function iterates through multiple pages, returning the array
		when all the pages have been successfully requested or retrieved from
		cache. Otherwise it returns NULL.
	**/
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
				// TODO: only return NULL if we are caching
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
			
			$batch = $this->_formatPeople($response);
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
				// TODO: Only return NULL if we are caching
				return NULL;
			}
		}
		
		return $friends;
	}

	/**
		getPublicTimeline - gets the most recent 20 tweets on the 
		public timeline. This is a non-caching request.
	**/
	public function getPublicTimeline() {
		$service = 'statuses/public_timeline';
		$public  = array();

		$response = $this->_doTwitterApiRequest($service, NULL, false);
		
		if (!empty($response)) {
			//print_r($response[0]);
			$public = $this->_formatTweets($response);
		}

		return $public;
	}
	
		
	
	

	##
	## Formatting methods
	##
	
	protected function _formatTweets($response) {
		$tweets = array();
		
		foreach($response as $message) {
			$tweets[] = $this->_formatTweet($message);
		}
		
		return $tweets;
	}
	
	protected function _formatTweet($response) {
		$tweet = (object) NULL;
		$tweet->id      = $response->id;
		$tweet->created = $response->created_at;
		$tweet->text    = $response->text;
		$tweet->user    = $this->_formatPerson($response->user);

		if (!empty($response->favorited)) {
			$tweet->favourited = $response->favorited;
		}

		if (!empty($response->in_reply_to_screen_name)) {
			$tweet->replyToUser = $response->in_reply_to_screen_name;
		}

		if (!empty($response->in_reply_to_status_id)) {
			$tweet->replyToStatus = $response->in_reply_to_status_id;
		}

		if (!empty($response->in_reply_to_user_id)) {
			$tweet->replyToUserId = $response->in_reply_to_user_id;
		}

		if (!empty($response->source)) {
			$tweet->source = $response->source;
		}

		return $tweet;	
	}

	protected function _formatPeople($response) {
		$friends = array();
		
		foreach($response as $person) {
			$friends[] = $this->_formatPerson($person);
		}

		return $friends;	
	}
	
	protected function _formatPerson($user) {
		$person = (object) NULL;

		$person->id        = $user->id;
		$person->username  = $user->screen_name;
		$person->fullname  = $user->name;
		$person->image     = $user->profile_image_url;
		$person->followers = $user->followers_count;
		$person->friends   = $user->friends_count;

		if (!empty($user->description)) {
			$person->bio         = $user->description;
		}

		if (!empty($user->url)) {
			$person->website     = $user->url;
		}

		if (!empty($user->location)) {
			$person->location = $user->location; 
		}

		if (!empty($user->protected)) {
			$person->protected = $user->protected; 
		}

		if (!empty($user->statuses_count)) {
			$person->updates   = $user->statuses_count; 
		}

		if (!empty($user->favourites_count)) {
			$person->favourites = $user->favourites_count; 
		}


		if (!empty($user->status)) {
			$person->latestTweet = $user->status->text; 
		}

		return $person;
	}



	##
	##
	##

	protected function _doTwitterApiRequest($service, $params=NULL, $cache=true) {
		$url  = "{$this->twitterBase}{$service}.{$this->format}";
		
		$cost = $this->serviceCost[$service];
		//echo " [{$serviceCost}<{$this->requestLimit}]";

		if ($cost==0 || $this->hasRequests() || ($cost<=$this->requestLimit)) {
			$response = $this->_doHttpApiRequest('GET', $url, $params, $cache);
			$this->requestLimit =  $this->requestLimit - $cost;
		} else {
			// Try an offline cache request
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
			echo '>';
			return $this->http->getCachedUrl($url);
		} else {
			return $this->http->getUrl($url, $cache);
		}
	}
	
}


?>