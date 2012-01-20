<?php
/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

class WDeleteAction extends CAction
{

	public function run()
	{
		$model = $this->controller->getModel();
		if ($model->delete()) {
			$this->controller->sendResponse(200, array('id' => $model->id));
		} else {
			$this->controller->sendResponse(500);
		}
	}

}