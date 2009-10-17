<?php

class WeFollowApi {
	var $http;
	var $parser;
	
	var $tagBaseUrl = 'http://wefollow.com/twitter/';
	var $siteBase   = 'http://wefollow.com';

	// Iterator methods
	var $nextUrl;
	var $prevUrl;
	
	public function getTaggedPeople($tag) {
		$html = $this->_getRawData($tag); 
		//echo "TaggedPeople: "; print_r($html);
		if ($html) {
			$pageData = $this->_scrapeTagPage($html);
			//print_r($pageData);

			// Set up potential iterators
			if (!empty($pageData->nextPage)) {
				//echo "NEXTPAGE: {$pageData->nextPage}\n";
				$this->nextUrl = $this->siteBase . $pageData->nextPage;
				
				## Deal with current borkedness of wefollow pagination
				if (strpos($this->nextUrl, 'twitter//page')!==false) {
					$realTag = $this->_getRawTag($tag);
					//echo "WARN: Next URL doesn't contain tag ($realTag) [{$this->nextUrl}]\n";
					$this->nextUrl = str_replace('twitter//page', "twitter/{$realTag}/page", $this->nextUrl);
					//echo "WARN: Changed next URL to [{$this->nextUrl}]\n";
				}
			}
		
			if (!empty($pageData->prevPage)) {
				$this->prevUrl = $this->siteBase . $pageData->prevPage;
			}

			return $pageData->people;
		} else {
			echo "WARN: No followers found for {$tag}\n";
		}
		
		return NULL;
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
		
		$tweeters = $dom->find('#results div.result_row');
		if(!empty($tweeters)) {
			foreach($tweeters as $tweeter) {
				$person = (object) NULL;
				//echo "Tweeter: {$tweeter->innertext}\n\n";

				// Grab the twitter username				
				$nameLink = $tweeter->find('.result_header strong a', 0);
				$person->username = $nameLink->plaintext;
				
				// Grab the userimage
				$imageData = $tweeter->find('.result_thumbnail a img', 0);
				$person->image = $imageData->src;
				
				// Followers
				$followerData = $tweeter->find('.follower_count', 0);
				$followers = str_replace(',', '', $followerData->plaintext);
				$person->followers = $followers;

				$pagedata->people[] = $person;
				//print_r($person); break;
			}		
		}
		
		// Get the page tag
		$tagInfo = $dom->find('#column-main h2', 0);
		$pagedata->tag = $tagInfo->plaintext;

		// Get the total number of followers
		$totalInfo  = $dom->find('#main_content span.user_count', 0);
		if ($totalInfo->plaintext) {
			if (preg_match('/^(\d+,?\d*)/', $totalInfo->plaintext, $matches)) {
				$total = str_replace(',', '', $matches[1]);
				$pagedata->totalFollowers = intval($total);
			}
		}
		
		// Get next and previous pages
		$navInfo = $dom->find('#main_content a img.more-prev-btn');
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
			echo "It's a file";
			return file_get_contents($tag);
		} elseif (preg_match('/^http\:\/\//', $tag)) {
			//echo "Has full url: [{$tag}]\n";
			return $this->_getUrl($tag);
		} else {
			echo "ERROR: Cannot determine urltype: [{$tag}]\n";
		}
	}
	
	protected function _getRawTag($tag) {
		//echo "INFO: Retrieve RawTag for {$tag}\n";
		if (preg_match('/^(\w+)$/', $tag, $matches)) {
			//echo "It's a partial tag\n";
			return $matches[1];
		} elseif (file_exists($tag)) {
			return NULL;
		} elseif (preg_match('/^http:\/\/wefollow.com\/twitter\/([^\/]+)/', $tag, $matches)) {
			return $matches[1];
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
		//echo "INFO: Getting URL: {$url}\n";
		return $this->http->getUrl($url);
	}
	
	protected function _initParser() {
		$this->parser = new HtmlParser();
	}
}

?>