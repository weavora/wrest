<?php

class TestController extends CController
{

	public function actionIndex()
	{
		Yii::app()->end(200);
	}



}