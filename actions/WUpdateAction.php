<?php
/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

class UpdateAction extends CAction
{

	public $scenario = '';

	public function run()
	{
		$requestAttributes = Yii::app()->request->getAllRestParams();

		$model = $this->controller->getModel($this->scenario);

		$paramsList = $model->getUpdateAttributes();

		$attributes = array_intersect_key($requestAttributes, $paramsList);

		$model->attributes = $attributes;

		if ($model->save()) {
			$this->controller->sendResponse(200, $model->getAllAttributes());
		} else {
			$this->controller->sendResponse(403, array('errors' => $model->getErrors()));
		}
	}

}