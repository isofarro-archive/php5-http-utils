<?php

class HttpClient {
	protected $request;
	protected $response;

	// The actual transfer client
	protected $client;


	public function getUrl($url) {
		$request = new HttpRequest($url);
	}



}

?>