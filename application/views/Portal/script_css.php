<!-- Bootstrap core CSS -->
<link href="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo $this->config->item('base_url'); ?>application/assets/portal/css/style.css" rel="stylesheet">


<?php 
$path_to_custom_portal = $this->config->item('base_url'). "application/assets/portal/css/".$portal['title'].".css";
if (file_exists($path_to_custom_portal)) { ?>
<!-- Custom portal CSS -->
<link href="<?php echo $path_to_custom_portal; ?>" rel="stylesheet">
<?php } ?>
<!-- Documentation extras -->
<link href="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/docs/assets/css/src/pygments-manni.css" rel="stylesheet">
<link href="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/docs/assets/css/src/anchor.css" rel="stylesheet">
<link href="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/docs/assets/css/src/docs.css" rel="stylesheet">

<!--[if lt IE 9]><script src="../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/docs/assets/js/ie-emulation-modes-warning.js"></script>

<!-- jquery  AND TTPEAHEAD-->
<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>application/assets/js/jquery/jquery-2.1.3.js"></script>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Favicons -->
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="icon" href="/favicon.ico">

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/js/typeahead.js"></script>
<script src="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/js/datepicker.js"></script>
<script src="<?php echo $this->config->item('base_url'); ?>application/assets/bootstrap/js/main.js"></script>
	
<script src="<?php echo $this->config->item('base_url'); ?>application/assets/js/tinymce/tinymce.min.js"></script>

<link href=" <?php echo $this->config->item('base_url'); ?>application/assets/js/DataTables-1.10.7/media/css/jquery.dataTables.min.css" rel="stylesheet">
<script  type="text/javascript" charset="utf8" src="<?php echo $this->config->item('base_url'); ?>application/assets/js/DataTables-1.10.7/media/js/jquery.dataTables.js" ></script>
<script  type="text/javascript" charset="utf8" src="<?php echo $this->config->item('base_url'); ?>application/assets/js/DataTables-1.10.7/media/js/datatables.bootstrap.js" ></script>

<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>application/assets/js/mrweb/functions.js"></script>