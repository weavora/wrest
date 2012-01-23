<?php
/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

class JsonResponse extends WRestResponse{

	protected function _getContentType()
	{
		return "application/json";
	}

	public function setParams($params = array())
	{
		$this->_body = CJSON::encode($params);
		return $this;
	}
}


