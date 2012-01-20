<?php

class WCreateAction extends CAction
{

	public $scenario = '';

	public function run()
	{
		$requestAttributes = $this->controller->request->getAllRestParams();

		$model = $this->controller->getModel($this->scenario);

		$paramsList = $model->getCreateParams();
		$attributes = array_intersect_key($requestAttributes, $paramsList);
		
		$model->attributes = $attributes;

		if ($model->save()) {
			$this->controller->_sendResponse(200, $model->getParams());
		} else {
			$this->controller->_sendResponse(500, array('errors' => $model->getErrors()));
		}
	}

}