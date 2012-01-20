<?php

class WHttpRequest extends CHttpRequest
{

	private $_restParams = array();

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
	 * return all posible params from request
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
		if(isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json'){
			$requestBody = file_get_contents("php://input");
			$this->_restParams = array_merge((array)json_decode($requestBody), $this->_restParams);
		}
		return $this->_restParams;
	}

	/**
	 * Returns the named GET or POST parameter value.
	 * If the GET or POST parameter does not exist, the second parameter to this method will be returned.
	 * If both GET and POST contains such a named parameter, the GET parameter takes precedence.
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
		if(!$param)
			$param = isset($_GET[$name]) ? $_GET[$name] : null;
		if(!$param)
			$param = isset($this->_restParams[$name]) ? $this->_restParams[$name] : null;
		
		return $param ? $param : $defaultValue;
	}

}
