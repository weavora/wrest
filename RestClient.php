<?php

/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

/**
 * A simple client for testing
 */
class RestClient
{

	//---
	protected $apiKey = null;
	protected $sharedKey = null;
	protected $urlBase = null;

	public function __construct($urlBase, $apiKey, $sharedKey)
	{
		$this->urlBase = $urlBase;
		$this->apiKey = $apiKey;
		$this->sharedKey = $sharedKey;
	}

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

	public function getHttpCode()
	{
		return $this->httpCode;
	}

	public function httpRequest($url, $postfields = array(), $method = "GET")
	{
		$postfields = array_merge($postfields, array('format' => 'json'));

		foreach ($postfields as $key => $value) {
			if (is_null($value)) {
				unset($postfields[$key]);
			}
		}

		$url = $this->urlBase . $url;

		$ci = curl_init();
		/* Curl settings */

		curl_setopt($ci, CURLOPT_HEADER, false);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

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
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
				}
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
		return (array) json_decode($response);
	}

}
