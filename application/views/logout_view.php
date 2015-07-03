<?php 
$this->view('Portal/header');
$this->view('Portal/menu');
?>
<div class="container main">
<div class="row">
        <div class="col-lg-6 col-sm-6 well">
        <?php 
        $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform");
        echo form_open("login?status=logout", $attributes);?>
        <fieldset>
             <legend><?php echo $this->lang->line('login_logout');?></legend>
             <div class="form-group">
             <div class="row colbox">
             <div class="col-lg-4 col-sm-4">
                  <label for="txt_username" class="control-label"><?php echo $this->lang->line('login_email');?></label>
             </div>
             <div class="col-lg-4 col-sm-4">
                  <span><?php echo $this->session->userdata('username'); ?> </span>
             </div>
             </div>
             </div>
             <div class="form-group">
             <div class="col-lg-12 col-sm-12 text-center">
                  <input id="btn_login" name="btn_logout" type="submit" class="btn btn-default" value="<?php echo $this->lang->line('login_logout');?>" />
             </div>
             </div>
        </fieldset>
        <?php echo form_close(); ?>
        <?php echo $this->session->flashdata('msg'); ?>
        </div>
   </div>
</div>
<?php
$this->view('Portal/footer');