<form method = "POST" action="<?php echo $module_name;?>/ajax" >
	<input type='hidden' name='action' value='<?php echo $modalaction; ?>'>
	<input type='hidden' name='nonce' value='<?php echo $nonce;?>'>
	<input type='submit' class='btn btn-primary' value='download' name='download'>
</form>
