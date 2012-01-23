<?php

error_reporting( E_ALL | E_STRICT );

require(dirname(__FILE__).'/../../../../../framework/yiit.php');
require dirname(__FILE__).'/ResultPrinter.php';

Yii::createWebApplication(require(dirname(__FILE__) . '/../../../config/main.php'));