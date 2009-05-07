<?php

class HttpResponse {
	protected $version;
	protected $status;
	protected $statusMsg;
	protected $headers;
	protected $body;

	public function __construct() {
		$this->headers = new HttpHeaders();	
	}
	
	public function getVersion() {
		return $this->version;
	}

	public function setVersion($version) {
		$this->version = $version;
	}
	
	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}
	
	public function getStatusMsg() {
		return $this->statusMsg;
	}

	public function setStatusMsg($statusMsg) {
		$this->statusMsg = $statusMsg;
	}
	

	public function getBody() {
		return $this->body;
	}

	public function setBody($body) {
		$this->body = $body;
	}
	

	public function setHeaders($headers) {
		foreach($headers as $name=>$value) {
			$this->headers->setHeader($name, $value);
		}
	}

	public function addHeader($header, $value) {
		$this->headers->setHeader($header, $value);
	}

	public function hasHeader($header) {
		return $this->headers->hasHeader($header);
	}
	
	public function getHeader($header) {
		return $this->headers->getHeader($header);
	}
}

?>