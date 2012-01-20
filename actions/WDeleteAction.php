<?php

class WDeleteAction extends CAction
{

	public function run()
	{
		$model = $this->controller->getModel();
		if ($model->delete()) {
			$this->controller->_sendResponse(200, array('id' => $model->id));
		} else {
			$this->controller->_sendResponse(500);
		}
	}

}