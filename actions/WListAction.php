<?php

class WListAction extends CAction
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
		$c->limit = (int)(($limit = $this->controller->request->getParam($this->limit)) ? $limit : -1);
		$page = (int)$this->controller->request->getParam($this->page) - 1;
		$c->offset = ($offset = $limit * $page) ? $offset : 0;
		$c->order = ($order = $this->controller->request->getParam($this->order)) ? $order : $model->getMetaData()->tableSchema->primaryKey;

		$models = $model->findAll($c);
		$result = array();
		if ($models) {
			foreach ($models as $item) {
				$result[] = $item->getParams();
			}
		}

		$this->controller->_sendResponse(200, $result);
	}

}
