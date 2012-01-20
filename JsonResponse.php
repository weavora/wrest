<?php

class JsonResponse extends WRestResponse{

	public function getContentType()
	{
		return "application/json";
	}

	public function setParams($params = array())
	{
		$this->body = CJSON::encode($params);
		return $this;
	}
}


