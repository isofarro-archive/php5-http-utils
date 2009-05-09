<?php

class WeFollowApi {
	var $http;
	var $parser;
	
	var $tagBaseUrl = 'http://wefollow.com/tag/';
	var $siteBase   = 'http://wefollow.com';

	// Iterator methods
	var $nextUrl;
	var $prevUrl;
	
	public function getTaggedPeople($tag) {
		$html = $this->_getRawData($tag); 
		//echo "TaggedPeople: "; print_r($html);
		$pageData = $this->_scrapeTagPage($html);
		//print_r($pageData);

		// Set up potential iterators
		if (!empty($pageData->nextPage)) {
			$this->nextUrl = $this->siteBase . $pageData->nextPage;
		}
		
		if (!empty($pageData->prevPage)) {
			$this->prevUrl = $this->siteBase . $pageData->prevPage;
		}

		return $pageData->people;
	}
	
	public function hasNext() {
		return !empty($this->nextUrl);
	}
	
	public function next() {
		return $this->getTaggedPeople($this->nextUrl);
	}
	
	public function hasPrevious() {
		return !empty($this->prevUrl);
	}
	
	public function previous() {
		return $this->getTaggedPeople($this->prevUrl);
	}

	
	protected function _scrapeTagPage($html) {
		$dom = $this->_parseHtml($html);
		
		$pagedata = (object) NULL;
		$pagedata->people = array();
		
		$tweeters = $dom->find('#column-main div.tweeters-list');
		if(!empty($tweeters)) {
			foreach($tweeters as $tweeter) {
				$person = (object) NULL;
				//echo "Tweeter: {$tweeter->innertext}\n\n";

				// Grab the twitter username				
				$nameLink = $tweeter->find('h3 a.fn', 0);
				$person->username = $nameLink->plaintext;
				
				// Grab the bio
				$bioData  = $tweeter->find('p', 0);
				if($bioData->plaintext) {
					$person->bio      = $bioData->plaintext;
				}
				
				// Grab the userimage
				$imageData = $tweeter->find('img.user-image', 0);
				$person->image = $imageData->src;
				
				// Followers
				$followerData = $tweeter->find('.follower-number', 0);
				$followers = str_replace(',', '', $followerData->plaintext);
				$person->followers = $followers;
				
				// Latest Tweet
				$tweetInfo = $tweeter->find('.latest-tweet p', 0);
				if ($tweetInfo->plaintext) {
					$person->latestTweet = $tweetInfo->plaintext;
				}

				// Full name
				$nameInfo = $tweeter->find('.other-details p', 0);
				if ($nameInfo->plaintext) {
					$person->fullname = $nameInfo->plaintext;
				}
				
				// Website
				$siteInfo = $tweeter->find('.other-details a', 0);
				if ($siteInfo->href) {
					$person->website = $siteInfo->href;
				}
				
				// Tags
				$tagInfo = $tweeter->find('.other-details p a');
				if (count($tagInfo)>0) {
					$person->tags = array();
					foreach($tagInfo as $tagLink) {
						$person->tags[] = $tagLink->plaintext;
					}
				}
				
				

				// Grab the rank
				$rankInfo = $tweeter->find('.rank', 0);
				if ($rankInfo->plaintext) {
					$person->rank     = $rankInfo->plaintext;
				}
				
				// Get follower change
				$changeInfo = $tweeter->find('.new-follower-number', 0);
				if ($changeInfo) {
					$person->followerChange = $changeInfo->plaintext;
				}
			
			
				$pagedata->people[] = $person;

			}		
		}
		
		// Get the page tag
		$tagInfo = $dom->find('#column-main h2', 0);
		$pagedata->tag = $tagInfo->plaintext;

		// Get the total number of followers
		$totalInfo  = $dom->find('#column-main div.total-followers', 0);
		if ($totalInfo->plaintext) {
			if (preg_match('/tag: (\d+,?\d*)/', $totalInfo->plaintext, $matches)) {
				$total = str_replace(',', '', $matches[1]);
				$pagedata->totalFollowers = intval($total);
			}
		}
		
		// Get next and previous pages
		$navInfo = $dom->find('#column-main a img.more-prev-btn');
		foreach($navInfo as $navItem) {
			$link = $navItem->parent->href;

			if (preg_match('/btn_more.gif$/', $navItem->src)) {
				$pagedata->nextPage = $link;
			} elseif(preg_match('/btn_prev.gif$/', $navItem->src)) {
				$pagedata->prevPage = $link;
			} else {
				echo "WARN: Unknown button: [{$navItem->src}]\n";
			}
		}

		
		$dom->clear();
		return $pagedata;
	}	

	protected function _parseHtml($html) {
		if (empty($this->parser)) {
			$this->_initParser();
		}
		return $this->parser->parseHtml($html);
	}	
	
	protected function _getRawData($tag) {
		$this->nextUrl = NULL;
		$this->prevUrl = NULL;
		if (preg_match('/^\w+$/', $tag)) {
			//echo "It's a partial tag\n";
			return $this->_getTagPage($tag);
		} elseif (file_exists($tag)) {
			//echo "It's a file";
			return file_get_contents($tag);
		} elseif (preg_match('/^http\:\/\//', $tag)) {
			//echo "Has full url: [{$tag}]\n";
			return $this->_getUrl($tag);
		} else {
			echo "ERROR: Cannot determine urltype: [{$tag}]\n";
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
		$this->parser = new HtmlParser();
	}
}

?>