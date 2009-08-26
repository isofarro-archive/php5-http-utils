<?php
/****
interface HttpRequestBase {
	//All the methods the request object must implement

	
}
****/

/***

class HttpRequest implements HttpRequestBase {
	// Could just use the existing class with a few alterations
}

class HttpDecorator implements HttpRequestBase {
	protected $request;
	// Basically maps all functions to $this->request->methodName()

}

HttpOauthDecorator extends HttpDecorator {
	// Override necessary methods, calling
	// $this->request->methodName() where appropriate

}

***/

?>