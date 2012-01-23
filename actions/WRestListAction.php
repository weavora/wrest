<?php
/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

class WRestListAction extends CAction
{

	public $filterBy = array();
	public $page = 'page';
	public $limit = 'limit';
	public $order = 'order';

	public function run()
	{

		$c = new CDbCriteria();

		foreach ($this->filterBy as $key => $val) {
			if (!is_null(Yii::app()->request->getParam($val)))
				$c->compare($key, Yii::app()->request->getParam($val));
		}

		$model = $this->controller->getModel();
		$c->limit = (int)(($limit = Yii::app()->request->getParam($this->limit)) ? $limit : -1);
		$page = (int)Yii::app()->request->getParam($this->page) - 1;
		$c->offset = ($offset = $limit * $page) ? $offset : 0;
		$c->order = ($order = Yii::app()->request->getParam($this->order)) ? $order : $model->getMetaData()->tableSchema->primaryKey;

		$models = $model->findAll($c);
		$result = array();
		if ($models) {
			foreach ($models as $item) {
				$result[] = $item->getAllAttributes();
			}
		}

		$this->controller->sendResponse(200, $result);
	}

}
