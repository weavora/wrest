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
	protected $_response = null;
	protected $_modelName = "";
	protected $_availableFormats = array('json');

	public function __construct($id, $module = null)
	{
		$this->_modelName = ucfirst($this->_modelName);

		Yii::app()->setComponent('request', Yii::createComponent(array(
					'class' => 'ext.wrest.WHttpRequest',
				)));

		Yii::app()->request->parseJsonParams();
		Yii::app()->request->getAllRestParams();

		$this->request->setFormat();
		$this->_response = WRestResponse::factory($this->request->getFormat());

		parent::__construct($id, $module);
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
			$bodyParams = CArray::merge($bodyParams, $this->_response->getErrorMessage($status));
		}
		$this->_response->setStatus($status);
		$this->sendHeaders();
		echo $this->_response->setParams($bodyParams)->getBody();
		Yii::app()->end();
	}

	public function sendHeaders()
	{
		$headers = $this->_response->getHeaders();
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
