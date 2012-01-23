<?php
$this->pageTitle = Yii::app()->name . ' - Test Form functionality';
?>

<h1>Test Form Functionality</h1>

<?php
$form = $this->beginWidget('CActiveForm', array(
	'id' => 'contact-form',
	'htmlOptions' => array('enctype'=>'multipart/form-data')
	));
?>

<div class="row">
	<?php echo $form->labelEx($model, 'textField'); ?>
	<?php echo $form->textField($model, 'textField'); ?>
	<?php echo $form->error($model, 'textField'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'checkBox'); ?>
	<?php echo $form->checkBox($model, 'checkBox'); ?>
	<?php echo $form->error($model, 'checkBox'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'dropDownList'); ?>
	<?php echo $form->checkBoxList($model, 'dropDownList', array('1' => 'Select first item', '2' => 'Select second item',)); ?>
	<?php echo $form->error($model, 'dropDownList'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'passwordField'); ?>
	<?php echo $form->passwordField($model, 'passwordField'); ?>
	<?php echo $form->error($model, 'passwordField'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'textArea'); ?>
	<?php echo $form->textArea($model, 'textArea'); ?>
	<?php echo $form->error($model, 'textArea'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model, 'fileField'); ?>
	<?php echo $form->fileField($model, 'fileField'); ?>
	<?php echo $form->error($model, 'fileField'); ?>
</div>

<div class="row buttons">
	<?php echo CHtml::submitButton('submit'); ?>
</div>

<?php $this->endWidget(); ?>

