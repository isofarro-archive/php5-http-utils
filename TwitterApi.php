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
		
		// Timeline methods
		'statuses/public_timeline'  => 1,
		'statuses/user_timeline'    => 1,
		
		// User methods
		'statuses/friends'          => 1,

		// Account methods
		'account/rate_limit_status' => 0
	);

	// TODO: create an OAuth section for this
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
	### Twitter Search services calls
	###
	
	public function search($query, $options=false) {
		$service = 'search';
		$search = array(
			'lang' => 'en',
			'rpp'  => 100,
			
		);
		
		if (is_array($options)) {
			$search = array_merge($search, $options);
		}
		
		$search['q'] = $query;
		
		$response = $this->_doSearchApiRequest($service, $search);
		$results  = $this->formatSearchResults($response->results);
		return $results;
	}
	
	public function searchAll() {
	
	}



	###
	### Twitter Timeline services calls
	###

	/**
		getPublicTimeline - gets the most recent 20 tweets on the 
		public timeline. This is a non-caching request.
	**/
	public function getPublicTimeline() {
		$service = 'statuses/public_timeline';
		$timeline  = array();

		$response = $this->_doTwitterApiRequest($service, NULL, false);
		
		if (!empty($response)) {
			//print_r($response[0]);
			$timeline = $this->_formatTweets($response);
		}

		return $timeline;
	}
	
	/**
		getUserTimeline - gets the most recent 20 tweets for the specified
		user.
	**/
	public function getUserTimeline($user, $page=1) {
		$service = 'statuses/user_timeline';
		$timeline = array();

		$response = $this->_doTwitterApiRequest(
			$service, 
			array('id' => $user)	
		);
		
		if (is_null($response)) {
			//echo "WARN: A null response received\n";
			// TODO: Allow access to a protected profile through authentication
			return NULL;
		} elseif (!empty($response)) {
			//print_r($response[0]);
			$timeline = $this->_formatTweets($response);
		}
		
		return $timeline;
	}
	


	###
	### Twitter User services calls
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



	###
	### Twitter Account services calls
	###
	
	public function getRateLimitStatus() {
		$service = 'account/rate_limit_status';
		$response = $this->_doTwitterApiRequest($service, NULL, false);
		return $response;
	}

	

	##
	## Formatting methods
	##
	
	/**
		_formatTweet - formats a list of tweets into a clean data array
	**/
	protected function _formatTweets($response) {
		$tweets = array();
		
		foreach($response as $message) {
			$tweets[] = $this->_formatTweet($message);
		}
		
		return $tweets;
	}
	
	/**
		_formatTweet - formats a single tweet into a clean data object
	**/
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


	/**
		_formatPeople - formats a list of people into a clean data array
	**/
	protected function _formatPeople($response) {
		$friends = array();
		
		foreach($response as $person) {
			$friends[] = $this->_formatPerson($person);
		}

		return $friends;	
	}
	
	/**
		_formatPerson - formats a single user into a clean data object
	**/
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
			$person->location    = $user->location; 
		}

		if (!empty($user->created_at)) {
			$person->joined      = $user->created_at;
		}

		if (!empty($user->protected)) {
			$person->protected   = $user->protected; 
		}

		if (!empty($user->statuses_count)) {
			$person->updates     = $user->statuses_count; 
		}

		if (!empty($user->favourites_count)) {
			$person->favourites  = $user->favourites_count; 
		}


		if (!empty($user->status)) {
			$person->latestTweet = $user->status->text; 
		}

		return $person;
	}


	protected function formatSearchResults($results) {
		$tweets = array();
		foreach($results as $result) {
			$tweets[] = $this->formatSearchResult($result);
		}
		return $tweets;
	}

	protected function formatSearchResult($result) {
		$tweet = (object) NULL;
		$tweet->id      = $result->id;
		$tweet->created = $result->created_at;
		$tweet->text    = $result->text;
		$tweet->user    = $result->from_user;

		if (!empty($result->to_user_id)) {
			$tweet->replyToUserId = $result->to_user_id;
		}

		if (!empty($result->iso_language_code)) {
			$tweet->lang = $result->iso_language_code;
		}

		if (!empty($result->profile_image_url)) {
			$tweet->user_image = $result->profile_image_url;
		}

		return $tweet;
	}




	##
	## HTTP request methods
	##

	/**
		_doTwitterApiRequest is a twitter.com wrapper around the HTTP call.
		Returns a data object that has already been json decoded.
		Also handles the request service call cost - offsetting the rate limit.
		It tries a cache request if there are no request credits left.
	**/
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
	
	/**
		_doTwitterApiRequest is a search.twitter.com wrapper around the 
		HTTP call. Returns a data object that has already been json decoded.
	**/
	protected function _doSearchApiRequest($service, $params=NULL, $cache=true) {
		$url  = "{$this->searchBase}{$service}.{$this->format}";
	
		$response = $this->_doHttpApiRequest('GET', $url, $params, $cache);
		return json_decode($response);
	}
	
	/**
		_doHttpApiRequest - creates the query string.
	**/
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
	
	/**
		_getUrl = wraps around the HttpClient object which actions
		the request.
	**/
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