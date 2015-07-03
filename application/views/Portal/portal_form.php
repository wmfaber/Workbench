<?php
echo $form->open;
foreach($form->element as $key => $element){
	echo "<div class='form-group'>";
	if(isset($element['type'] )){	
	if($element['type'] != 'hidden'){
	echo $element['label'];
	}
	}
	echo $element['input'];
	echo "</div>";
}
echo "	<input type='hidden' name='nonce' value=". $nonce.">";
echo $form->submit;
echo $form->close;
