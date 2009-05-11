<?php


class HttpRequestFactory {

	public function create($decorators) {
		if (is_string($decorators)) {
			$decorators = array($decorators);
		}
		
		$request = new HttpRequest();
	
	}

}

?>