<?php

/**
 * Simple client for testing
 */
class RestClient
{

	protected $urlBase = '';
	protected $httpCode = null; //code of the last response

	public function get($url, $params = array('format' => 'json'))
	{
		return $this->httpRequest($url, $params, 'GET');
	}

	public function post($url, $params = array('format' => 'json'))
	{
		return $this->httpRequest($url, $params, 'POST');
	}

	public function delete($url, $params = array('format' => 'json'))
	{
		return $this->httpRequest($url, $params, 'DELETE');
	}

	public function put($url, $params = array('format' => 'json'))
	{
		return $this->httpRequest($url, $params, 'PUT');
	}

	protected function _convertParams($params)
	{
		return $result = http_build_query($params);
	}

	public function httpRequest($url, $postfields = array(), $method = "GET")
	{
		$url = $this->urlBase . $url;

		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ci, CURLOPT_HEADER, false);


		$postfields = $this->_convertParams($postfields);
		switch ($method) {
			case 'GET':
				$url .= "?" . $postfields;
				break;
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, true);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
			case 'PUT':
				//		curl_setopt($ci, CURLOPT_PUT, true);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
				}
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, "PUT");
				break;
		}
		curl_setopt($ci, CURLOPT_URL, $url);


		$response = curl_exec($ci);
		$this->httpCode = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		curl_close($ci);
		return $response;
	}

	public function getHttpCode()
	{
		return $this->httpCode;
	}

}