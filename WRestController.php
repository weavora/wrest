<?php

/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */
Yii::import("ext." . basename(__DIR__) . '.actions.*');
Yii::import("ext." . basename(__DIR__) . '.behaviors.*');

abstract class WRestController extends CController
{

	/**
	 * @var WRestResponse
	 */
	public $response = null;
	protected $_modelName = "";
	protected $_responseFormat = null;

	public function init(){
		$this->_modelName = ucfirst($this->_modelName);

		Yii::app()->setComponent('request', Yii::createComponent(array(
					'class' => 'ext.wrest.WHttpRequest',
				)));

		$this->request->parseJsonParams();
		$this->request->getAllRestParams();

		$this->request->setFormat($this->_responseFormat);
		$this->response = WRestResponse::factory($this->request->getFormat());

		return parent::init();
	}

	/**
	 * @desc
	 * @param type $status
	 * @param string $body
	 * @param type $content_type
	 */
	public function sendResponse($status = 200, $bodyParams = array())
	{
		if ($status != 200) {
			$bodyParams = CMap::mergeArray($bodyParams, $this->response->getErrorMessage($status));
		}
		$this->response->setStatus($status);
		$this->sendHeaders();
		echo $this->response->setParams($bodyParams)->getBody();
		Yii::app()->end();
	}

	public function sendHeaders()
	{
		$headers = $this->response->getHeaders();
		foreach ($headers as $header){
			header($header);
		}
	}

	/**
	 * @return CActiveRecord
	 */
	public function getModel($scenario = '')
	{
		$id = $this->request->getParam('id');
		$modelName = ucfirst($this->_modelName);

		if (empty($this->_modelName) && class_exists($modelName)) {
			$this->sendResponse(400);
		}

		if ($id) {
			$model = $modelName::model()->findByPk($id);
			if (is_null($model)) {
				$this->sendResponse(400);
			}
		} else {
			$model = new $modelName();
		}
		if ($scenario && $model)
			$model->setScenario($scenario);
		return $model;
	}

	/**
	 * @return WHttpRequest
	 */
	public function getRequest()
	{
		return Yii::app()->request;
	}

}
