<?php

class HttpClient {
	protected $request;
	protected $response;

	// The actual transfer client
	protected $client;


	public function getUrl($url) {
		$request = new HttpRequest($url);
		$response = $this->doRequest($request);
		//print_r($response);
		
		return $response->getBody();
	}


	public function doRequest($request) {
		$request->_initHttpHeaders();
		//print_r($request);
		switch($request->getMethod()) {
			case 'GET':
				return $this->doGet($request);
				break;
			case 'POST':
			case 'PUT':
			case 'DELETE':
			default:
				echo 'WARN: ', $request->getMethod(), " not implemented.\n";
				break;		
		}
	}
	
	public function doGet($request) {
		$response = NULL;
		$ch = curl_init();
		
		curl_setopt_array($ch, array(
			CURLOPT_URL            => $request->getUrl(),
			CURLOPT_HTTPGET        => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => true
		));
		
		// Convert to raw CURL headers and add to request
		$headers = $request->getHeaders();
		if (!empty($headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);            
		}

		$httpOutput = curl_exec($ch);
		//print_r($httpOutput);
		if ($httpOutput) {
			$response = $this->parseResponse($httpOutput);
		} else {
			$response = $this->_createErrorResponse($ch);
			echo "RESPONSE: "; print_r($response);
		}							
								
		curl_close($ch);
		return $response;
	}


	public function doPost($request) {
		$response = NULL;
		$ch = curl_init();
		
		$data = $request->getBody();

		if ($data) {
			curl_setopt_array($ch, array(
				CURLOPT_URL            => $request->getUrl(),
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => $data,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => true
			));

			// Convert to raw CURL headers and add to request
			$headers = $request->getHeaders();
			if (!empty($headers)) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);            
			}

			$httpOutput = curl_exec($ch);
			$response = $this->parseResponse($httpOutput);
		} else {
			echo "ERROR: No body to send.\n";
		}
		
		curl_close($ch);
		return $response;
	}

	public function doPut($request) {
		$ch = curl_init();

		$data = $request->getBody();
		
		if ($data) {
			curl_setopt_array($ch, array(
				CURLOPT_URL            => $request->getUrl(),
				CURLOPT_CUSTOMREQUEST  => 'PUT',
				CURLOPT_POSTFIELDS     => $data,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => true
			));

			// Convert to raw CURL headers and add to request
			$headers = $request->getHeaders();
			if (!empty($headers)) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);            
			}

			$httpOutput = curl_exec($ch);
			$response = $this->parseResponse($httpOutput);
		} else {
			echo "ERROR: No body to send.\n";
		}
		
		curl_close($ch);
		return $response;
	}

	public function doDelete($request) {
		$response = NULL;
		$ch = curl_init();
		
		curl_setopt_array($ch, array(
			CURLOPT_URL            => $request->getUrl(),
			CURLOPT_CUSTOMREQUEST  => 'DELETE',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => true
		));
		
		// Convert to raw CURL headers and add to request
		$headers = $request->getHeaders();
		if (!empty($headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);            
		}

		$httpOutput = curl_exec($ch);
		$response = $this->parseResponse($httpOutput);

		curl_close($ch);
		return $response;
	}


	protected function _parseResponse($output) {
		$response = new HttpResponse();
		
		if ($output) {
			$lines    = explode("\n", $output);
			$isHeader = true;
			$buffer   = array();
			
			foreach($lines as $line) {
				if ($isHeader) {
					if (preg_match('/^\s*$/', $line)) {
						// Header/body separator
						$isHeader = false;
					} else {
						// This is a real HTTP header
						if (preg_match('/^([^:]+)\:(.*)$/', $line, $matches)) {
							//echo "HEADER: [", $matches[1], ']: [', $matches[2], "]\n";
							$name  = trim($matches[1]);
							$value = trim($matches[2]);						
							$response->addHeader($name, $value);
						} else {
							// This is the status response
							//echo "HEADER: ", trim($line), "\n";
							if (preg_match(
										'/^(HTTP\/\d\.\d) (\d*) (.*)$/', 
										trim($line), $matches)
									) {
								$response->setStatus($matches[2]);
								$response->setStatusMsg($matches[3]);
								$response->setVersion($matches[1]);
							}
						}					
					}
				} else {
					$buffer[] = $line;
				}
			}
			// The buffer is the HTTP Entity Body
			$response->setBody(implode("\n", $buffer));
		}
		return $response;
	}
	
	protected function _createErrorResponse($ch) {
		$response = new HttpResponse();
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if ($statusCode==0) {
			$response->setStatus(502);
			$response->setStatusMsg('CURL Error');
		} else {
			$response->setStatus($statusCode);
			$response->setStatusMsg('CURL Response');
		}
		return $response;
	}

}

?>