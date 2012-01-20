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
	 *
	 * @var WRestResponse
	 */
	protected $_response = null;

	/**
	 * @var CHttpRequest
	 */
	protected $_modelName = "";
	protected $_availableFormats = array('json');

	/**
	 * Default response format
	 * either 'json' or 'xml'
	 */
	private $_format = 'json';
	private $_formatAttributeName = 'format';
	
	public function __construct($id, $module = null)
	{
		$this->_modelName = ucfirst($this->_modelName);

		Yii::app()->setComponent('request', Yii::createComponent(array(
					'class' => 'ext.wrest.WHttpRequest',
		)));

		Yii::app()->request->parseJsonParams();
		Yii::app()->request->getAllRestParams();

		$this->setFormat();
		$this->_response = WRestResponse::factory($this->_format);

		parent::__construct($id, $module);
	}

	protected function beforeAction($action)
	{
		$this->verifyRequest();
		return parent::beforeAction($action);
	}

	public function createModel($attributes, $scenario = '')
	{
		$model = $this->getModel($scenario);
		$model->attributes = $attributes;
		$model->save();
		return $model;
	}

	/**
	 * @desc
	 * @param type $status
	 * @param string $body
	 * @param type $content_type
	 */
	public function sendResponse($status = 200, $bodyParams = array())
	{
		if (empty($bodyParams) && (($this->action->id != 'list' && $this->action->id != 'get') || $status != 200)) {
			$bodyParams = CArray::merge($bodyParams, $this->_response->getErrorMessage($status));
		}
		echo $this->_response->setHeaders($status)->setParams($bodyParams)->send();
		Yii::app()->end();
	}

	protected function verifyRequest()
	{
		return true;
	}

	public function setFormat($format = null)
	{
		if ($format && in_array($format, $this->_availableFormats)) {
			$this->_format = $format;
		}
		if (!$this->_format) {
			//get format from one of requests type
			$format = Yii::app()->request->getParam($this->_formatAttributeName);
			$format = (empty($format)) ? Yii::app()->request->getPut($this->_formatAttributeName) : $format;
			$format = (empty($format)) ? Yii::app()->request->getDelete($this->_formatAttributeName) : $format;
		}
	}

	/**
	 * @return CActiveRecord
	 */
	public function getModel($scenario = '')
	{
		$id = Yii::app()->request->getParam('id');
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

	public function getRequest()
	{
		return Yii::app()->request;
	}

}
