<?php
/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

class WHttpRequest extends CHttpRequest
{

	private $_restParams = array();

	/**
	 * Default response format
	 * either 'json' or 'xml'
	 */
	private $_format = 'json';
	private $_formatAttributeName = 'format';
	protected $_availableFormats = array('json','xml');

	public function getPut($name, $defaultValue = null)
	{
		if ($this->_restParams === array())
			$this->_restParams = array_merge($this->getIsPutRequest() ? $this->getRestParams() : array(), $this->_restParams);
		return isset($this->_restParams[$name]) ? $this->_restParams[$name] : $defaultValue;
	}

	public function getDelete($name, $defaultValue = null)
	{
		if ($this->_restParams === array())
			$this->_restParams = array_merge($this->getIsDeleteRequest() ? $this->getRestParams() : array(), $this->_restParams);
		return isset($this->_restParams[$name]) ? $this->_restParams[$name] : $defaultValue;
	}


	/**
	 * return all posible params from a request
	 * @return array()
	 */
	public function getAllRestParams($ignorInlineParams = false)
	{
		if ($this->_restParams === array())
			$this->_restParams = array_merge(($this->getIsDeleteRequest() || $this->getIsPutRequest()) ? $this->getRestParams() : $_REQUEST, $this->_restParams);
		if($ignorInlineParams){
			$result = array();
			foreach ($this->_restParams as $key => $val){
				if(!preg_match('|^_|si', $key)){
					$result[$key] = $val;
				}
			}
			return $result;
		}
		return $this->_restParams;
	}


	public function parseJsonParams(){
		if(!isset($_SERVER['CONTENT_TYPE'])){
			return $this->_restParams;
		}
		
		$contentType = strtok($_SERVER['CONTENT_TYPE'], ';');
		if($contentType == 'application/json'){
			$requestBody = file_get_contents("php://input");
			$this->_restParams = array_merge((array)json_decode($requestBody), $this->_restParams);
		}
		return $this->_restParams;
	}

	/**
	 * Returns the named GET or POST parameter value.
	 * If the GET or POST parameter does not exist, the second parameter for this method will be returned.
	 * If both GET and POST contain such a named parameter, the GET parameter takes precedence.
	 * @param string $name the GET parameter name
	 * @param mixed $defaultValue the default parameter value if the GET parameter does not exist.
	 * @return mixed the GET parameter value
	 * @since 1.0.4
	 * @see getQuery
	 * @see getPost
	 */
	public function getParam($name,$defaultValue=null)
	{
		if(!isset($_GET[$name]) && isset($_GET['_'.$name])){
			$name = '_'.$name;
		}
		$param = isset($_GET[$name]) ? $_GET[$name] : null;
		if(is_null($param))
			$param = isset($_GET[$name]) ? $_GET[$name] : null;
		if(is_null($param))
			$param = isset($this->_restParams[$name]) ? $this->_restParams[$name] : null;
		
		return !is_null($param) ? $param : $defaultValue;
	}

	public function setFormat($format = null)
	{
		if ($format && in_array($format, $this->_availableFormats)) {
			$this->_format = $format;
		}
		if (!$this->_format) {
			//get format from one of the request types
			$format = Yii::app()->request->getParam($this->_formatAttributeName);
			$format = (empty($format)) ? Yii::app()->request->getPut($this->_formatAttributeName) : $format;
			$format = (empty($format)) ? Yii::app()->request->getDelete($this->_formatAttributeName) : $format;
			$this->_format = $format;
		}
	}

	public function getFormat(){
		return $this->_format;
	}

}
