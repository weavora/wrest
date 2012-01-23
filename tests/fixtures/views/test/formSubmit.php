<div class="row">
	<?php echo $form->textField; ?>
</div>

<div class="row">
	<?php echo $form->checkBox ?  "CheckBox is checked": "Not check"; ?>
</div>

<div class="row">
	<?php echo $form->dropDownList; ?>
</div>

<div class="row">
	<?php echo $form->passwordField; ?>
</div>

<div class="row">
	<?php echo $form->textArea; ?>
</div>

<div class="row">
	<?php echo $uploadedFileSaved ? $form->fileField->getName() : ""; ?>
</div>


