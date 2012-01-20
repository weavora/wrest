<?php
/**
 * @author Weavora Team <hello@weavora.com>
 * @link http://weavora.com
 * @copyright Copyright (c) 2011 Weavora LLC
 */

class WRestModelBehavior extends CActiveRecordBehavior
{

	public function getAllAttributes()
	{
		$owner = $this->getOwner();
		return $owner->getAttributes();
	}

	public function getCreateAttributes()
	{
		return $this->_getAttributesByScenario('insert');
	}

	public function getUpdateAttributes()
	{
		return $this->_getAttributesByScenario('update');
	}

	private function _getAttributesByScenario($scenario){
		$owner = $this->getOwner();
		$owner->setScenario($scenario);
		$validators = $owner->getValidators();
		$attributes = array();

		foreach ($validators as $validator){
			$attributes = array_merge($attributes, $validator->attributes);
		}
		return $attributes;
	}

}