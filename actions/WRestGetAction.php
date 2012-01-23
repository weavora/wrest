<?php
/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

class WRestGetAction extends CAction
{

	public function run()
	{
		$model = $this->controller->getModel();
		$this->controller->sendResponse(200, $model->getAllAttributes());
	}

}