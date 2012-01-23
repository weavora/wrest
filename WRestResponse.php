<?php
/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

abstract class WRestResponse
{
	protected $_body = '';
	protected $status = 200;

	public abstract function getContentType();

	public abstract function setParams($params = array());

	public function getBody()
	{
		return $this->_body;
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
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	public function getStatusCodeMessage($status, $isCode = true)
	{
		$codes = Array(
			200 => array('OK' => 'OK'),
			400 => array('Bad Request' => 'Bad Request'),
			401 => array('Unauthorized' => 'You must be authorized to view this page.'),
			402 => array('Payment Required' => 'Payment Required'),
			403 => array('Forbidden' => 'Forbidden'),
			404 => array('Not Found' => 'The requested URL was not found.'),
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
			'title' => $this->getStatusCodeMessage($status),
			'message' => $this->getStatusCodeMessage($status, false),
		);
	}

	public function getHeaders(){
		$headers = array();
		
		$status = $this->status;
		// set the status
		$statusHeader = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
		$headers[] = $statusHeader;
		// and the content type
		$headers[] = 'Content-type: ' . $this->getContentType();

		return $headers;
	}

	public function getStatus(){
		return $this->status;
	}

}