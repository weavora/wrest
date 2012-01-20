<?php

Yii::import("ext." . basename(__DIR__) . '.actions.*');
Yii::import("ext." . basename(__DIR__) . '.behaviors.*');

abstract class WRestController extends CController
{

	/**
	 *
	 * @var WRestResponse
	 */
	protected $response = null;

	/**
	 * @var CHttpRequest
	 */
	protected $request = null;
	protected $user = null;
	protected $modelName = "";
	protected $availableFormats = array('json');

	/**
	 * Default response format
	 * either 'json' or 'xml'
	 */
	private $format = 'json';

	public function init()
	{
//		Yii::app()->setComponents(array(
//			'class' => 'ext.wrest.WHttpRequest',
//				)
//		);
//		$component = Yii::createComponent(array(
//					'class' => 'ext.wrest.WHttpRequest',
//				));
//		Yii::app()->setComponent('request', $component);
		return parent::init();
	}

	public function __construct($id, $module = null)
	{
		//import rest actions


		$this->request = Yii::app()->request;
		$this->modelName = ucfirst($this->modelName);
		$this->user = Yii::app()->user;

		$this->request->parseJsonParams();
		$params = $this->request->getAllRestParams();

//		if (isset($params['access_token'])) {
//			$userToken = $params['access_token'];
//			$user = ApiUser::model()->findByToken($userToken);
//			if ($user) {
//				$this->user->setId($user->user_id);
//				$this->user->setState('token', $userToken);
//			}
//		}

		$this->_setFormat();
		$this->response = WRestResponse::factory($this->format);

		parent::__construct($id, $module);
	}

	protected function beforeAction($action)
	{
		$this->verifyRequest();
		return parent::beforeAction($action);
	}

	public function _createModel($attributes, $scenario = '')
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
	public function _sendResponse($status = 200, $bodyParams = array())
	{
		if (empty($bodyParams) && (($this->action->id != 'list' && $this->action->id != 'get') || $status != 200)) {
			$bodyParams = CArray::merge($bodyParams, $this->response->getErrorMessage($status));
		}
		echo $this->response->setHeaders($status)->setParams($bodyParams)->send();
		Yii::app()->end();
	}

	protected function checkAuth()
	{
		return true;
	}

	protected function verifyRequest()
	{
		return true;
	}

	private function _setFormat()
	{
		//get format from one of requests type
		$format = $this->request->getParam('format');
		$format = (empty($format)) ? $this->request->getPut('format') : $format;
		$format = (empty($format)) ? $this->request->getDelete('format') : $format;

		if ($format && in_array($format, $this->availableFormats)) {
			$this->format = $format;
		}
	}

	/**
	 * @return CActiveRecord
	 */
	public function getModel($scenario = '')
	{
		$id = Yii::app()->request->getParam('id');
		$modelName = ucfirst($this->modelName);

		if (empty($this->modelName) && class_exists($modelName)) {
			$this->_sendResponse(400);
		}

		if ($id) {
			$model = $modelName::model()->findByPk($id);
			if (is_null($model)) {
				$this->_sendResponse(400);
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
		return $this->request;
	}

}
