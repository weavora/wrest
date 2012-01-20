<?php

class WGetAction extends CAction
{

	public function run()
	{
		$model = $this->controller->getModel();
		$this->controller->_sendResponse(200, $model->getParams());
	}

}