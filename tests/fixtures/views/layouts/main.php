<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
          media="screen, projection"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
          media="print"/>
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
          media="screen, projection"/>
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

    <div id="header">
        <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
        <div id="user-info">
            <?php echo Yii::app()->user->isGuest ? "You are not loged in" : "You are loged in as " . Yii::app(
                )->user->getName(); ?>
        </div>
    </div>
    <!-- header -->

    <div id="mainmenu">
        <?php $this->widget(
            'zii.widgets.CMenu',
            array(
                'items' => array(
                    array('label' => 'Home', 'url' => array('/test/index')),
                    array('label' => 'Contact', 'url' => array('/test/contact')),
                    array('label' => 'Login', 'url' => array('/test/login'), 'visible' => Yii::app()->user->isGuest),
                    array(
                        'label' => 'Logout (' . Yii::app()->user->name . ')',
                        'url' => array('/test/logout'),
                        'visible' => !Yii::app()->user->isGuest
                    )
                ),
            )
        ); ?>
    </div>
    <!-- mainmenu -->

    <div id="content">
        <?php echo $content; ?>
    </div>

    <div class="clear"></div>

    <div id="footer">
        Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
        All Rights Reserved.<br/>
        <?php echo Yii::powered(); ?>
    </div>
    <!-- footer -->

</div>
<!-- page -->

</body>
</html>
