<?php

class HttpClient {
	protected $request;
	protected $response;

	// The actual transfer client
	protected $client;


	public function getUrl($url) {
		$request = new HttpRequest($url);
		return $this->doRequest($request);
	}


	public function doRequest($request) {
		print_r($request);
		switch($request->getMethod()) {
			case 'GET':
			case 'POST':
			case 'PUT':
			case 'DELETE':
			default:
				echo 'WARN: ', $request->getMethod(), " not implemented.\n";
				break;		
		}
	}

}

?>