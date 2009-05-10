<?php

// TODO Cache all GET requests heavily

class TwitterApi {
	var $searchBase  = 'http://search.twitter.com/';
	var $twitterBase = 'http://twitter.com/';
	
	var $format = 'json';

	var $http;

	public function getRequestToken() {
		return 'Hello world';
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
		$page = 1;
		
		while (count($response)>0) {
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
		}
		
		//print_r($friends);
		return $friends;
	}

	protected function _formatFriends($response) {
		$friends = array();
		
		foreach($response as $person) {
			$friend = (object) NULL;
			
			$friend->username    = $person->screen_name;
			$friend->image       = $person->profile_image_url;
			$friend->followers   = $person->followers_count;
			$friend->fullname    = $person->name;
			
			if ($person->description) {
				$friend->bio         = $person->description;
			}

			if ($person->url) {
				$friend->website     = $person->url;
			}

			if ($person->status) {
				$friend->latestTweet = $person->status->text; 
			}
			
			$friends[] = $friend;		
		}

		return $friends;	
	}

	protected function _doTwitterApiRequest($service, $params=NULL, $cache=true) {
		$url    = "{$this->twitterBase}{$service}.{$this->format}";
		return json_decode($this->_doHttpApiRequest('GET', $url, $params, $cache));
	}
	
	protected function _doHttpApiRequest($method, $url, $params, $cache=true) {
		if ($method=='GET') {
			if (!empty($params)) {
				$query = http_build_query($params);
				$url = "{$url}?{$query}";
			}
			return $this->_getUrl($url, $cache);
		} else {
			echo "ERROR: unsupported HTTP method: {$method}\n";
			return NULL;
		}
	}
	
	protected function _getUrl($url, $cache=true) {
		if (empty($this->http)) {
			$this->http = new HttpClient();
		}
		return $this->http->getUrl($url, $cache);
	}
	
}


?>