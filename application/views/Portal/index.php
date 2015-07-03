<?php 
$this->view('Portal/header');
$this->view('Portal/menu');
?>
<div class="container main">
<div class="row">
<?	
if(isset($side) && $side == true) 
{ ?>
	<div class="col-lg-4 col-md-4 well"><p>This is the side bar content.</p></div>
	<div class="col-lg-8 col-md-8">
	<?php
}

if(isset($template)){
	$this->view($template);
}else{
	$this->view('Portal/portal_'.$action);
}

$this->view('Portal/footer');