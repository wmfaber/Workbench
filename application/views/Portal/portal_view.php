<?php

echo $view;
if($add_button){
	echo $add_button;
	?>
	<!--<a data-target="#MRweb_Modal" class="btn btn-primary" data-toggle="modal" href = "/<?php echo $module_name; ?>/ajax?action=add&ajax=1 ">Toevoegen</a>
	<a data-target="#MRweb_Modal" class="btn btn-primary" data-toggle="modal" href = "/<?php echo $module_name; ?>/ajax?action=download&modalaction=download_all">Download</a>
	-->
	<?php 
}