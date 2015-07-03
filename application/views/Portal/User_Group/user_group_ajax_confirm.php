<form method = "POST" action="/<?php echo $module_name;?>/ajax/">
	<p>Weet u zeker dat uw deze combinatie wilt verwijderen?: <?php echo $record['id'];?></p>
	<input type='hidden' name='action' value='delete_record_detail'>
	<input type='hidden' name='id' value='<?php echo $record['id'];?>'>
	<input type='hidden' name='group_id' value='<?php echo $record['group_id'];?>'>
	<input type='hidden' name='nonce' value='<?php echo $nonce;?>'>
	<input type='submit' class='btn btn-primary' value='delete' name='delete'>
	<!-- <a onclick="Close_Modal('MRweb_Modal');" class='btn btn-warning' ><?php echo $this->lang->line('login_cancel');?></a> -->
</form>
