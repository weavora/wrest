<?php

class WRestModelBehavior extends CActiveRecordBehavior
{

	public function getParams()
	{
		$owner = $this->getOwner();
		return $owner->getAttributes();
	}

	public function getCreateParams()
	{
		return $this->_getAttributesByScenario('insert');
	}

	public function getUpdateParams()
	{
		return $this->_getAttributesByScenario('update');
	}

	private function _getAttributesByScenario($scenario){
		$owner = $this->getOwner();
		$owner->setScenario($scenario);
		$validators = $owner->getValidators();
		$attributes = array();

		foreach ($validators as $validator){
			$attributes = CArray::merge($attributes, $validator->attributes);
		}
		return $attributes;
	}

}