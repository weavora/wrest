<?php

/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */
class WRestCreateAction extends CAction
{

	public $scenario = '';

	public function run()
	{
		$requestAttributes = Yii::app()->request->getAllRestParams();

		$model = $this->controller->getModel($this->scenario);

		$paramsList = $model->getCreateAttributes();

		$attributes = array();
		foreach ($paramsList as $key) {
			if (isset($requestAttributes[$key])) {
				$attributes[$key] = $requestAttributes[$key];
			}
		}
		
		$model->attributes = $attributes;

		if ($model->save()) {
			$this->controller->sendResponse(200, $model->getAllAttributes());
		} else {
			$this->controller->sendResponse(500, array('errors' => $model->getErrors()));
		}
	}

}