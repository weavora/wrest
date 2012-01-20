<?php

abstract class WRestResponse
{
	protected $body = '';

	public abstract function getContentType();

	public abstract function setParams($params = array());

	public function send()
	{
		return $this->body;
	}

	/**
	 *
	 * @param string $type
	 * @return WRestResponse
	 */
	public static function factory($type)
	{
		$className = ucfirst($type) . "Response";
		return new $className();
	}

	/**
	 *
	 * @param string $status
	 * @return WRestResponse
	 */
	public function setHeaders($status)
	{
		// set the status
		$statusHeader = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		header($statusHeader);
		// and the content type
		header('Content-type: ' . $this->getContentType());

		return $this;
	}

	protected function _getStatusCodeMessage($status, $isCode = true)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			200 => array('OK' => 'OK'),
			400 => array('Bad Request' => 'Bad Request'),
			401 => array('Unauthorized' => 'You must be authorized to view this page.'),
			402 => array('Payment Required' => 'Payment Required'),
			403 => array('Forbidden' => 'Forbidden'),
			404 => array('Not Found' => 'The requested URL ' . Yii::app()->request->getRequestUri() . ' was not found.'),
			500 => array('Internal Server Error' => 'The server encountered an error processing your request.'),
			501 => array('Not Implemented' => 'The requested method is not implemented.'),
		);
		$result = "";
		if (isset($codes[$status])) {
			$code = ($isCode) ? array_keys($codes[$status]) : array_values($codes[$status]);
			$result = array_pop($code);
		}
		return $result;
	}

	public function getErrorMessage($status){
		return array(
			'code' => $status,
			'title' => $this->_getStatusCodeMessage($status),
			'message' => $this->_getStatusCodeMessage($status, false),
		);
	}

}