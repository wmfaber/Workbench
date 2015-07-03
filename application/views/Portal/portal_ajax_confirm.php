<form method = "POST" action="<?php echo $module_name;?>/ajax/">
	<p>Weet u zeker dat u dit item wilt verwijderen?: <?php echo $record['title'];?></p>
	<input type='hidden' name='action' value='delete_record'>
	<input type='hidden' name='id' value='<?php echo $record['id'];?>'>
	<input type='hidden' name='nonce' value='<?php echo $nonce;?>'>
	<input type='submit' class='btn btn-primary' value='delete' name='delete'>
	<!-- <a onclick="Close_Modal('MRweb_Modal');" class='btn btn-warning' ><?php echo $this->lang->line('login_cancel');?></a> -->
</form>
