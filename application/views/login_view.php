<?php 
$this->view('Portal/header');
$this->view('Portal/menu');
?>
<div class="container main">
<div class="row">
        <div class="col-lg-6 col-sm-6 well">
        <?php 
        $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform");
         Handle_Module_Class::form_open("login", $attributes);
        ?>
        <fieldset>
             <legend><?php echo $this->lang->line('login_login');?></legend>
             <div class="form-group">
             <div class="row colbox">
             <div class="col-lg-4 col-sm-4">
                  <label for="txt_username" class="control-label"><?php echo $this->lang->line('login_email');?></label>
             </div>
             <div class="col-lg-8 col-sm-8">
                  <input class="form-control" id="txt_username" name="txt_username" placeholder="<?php echo $this->lang->line('login_email');?>" type="text" value="<?php echo set_value('txt_username'); ?>" />
                  <span class="text-danger"><?php echo form_error('txt_username'); ?></span>
             </div>
             </div>
             </div>
             
             <div class="form-group">
             <div class="row colbox">
             <div class="col-lg-4 col-sm-4">
             <label for="txt_password" class="control-label"><?php echo $this->lang->line('login_password');?></label>
             </div>
             <div class="col-lg-8 col-sm-8">
                  <input class="form-control" id="txt_password" name="txt_password" placeholder="<?php echo $this->lang->line('login_password');?>" type="password" value="<?php echo set_value('txt_password'); ?>" />
                  <span class="text-danger"><?php echo form_error('txt_password'); ?></span>
             </div>
             </div>
             </div>
                            
             <div class="form-group">
             <div class="col-lg-12 col-sm-12 text-left">
                  <input id="btn_login" name="btn_login" type="submit" class="btn btn-default" value="<?php echo $this->lang->line('login_login');?>" />
                  <input id="btn_cancel" name="btn_cancel" type="reset" class="btn btn-default" value="<?php echo $this->lang->line('login_cancel');?>" />
             </div>
             </div>
             <div class="form-group">
             <div class="col-lg-12 col-sm-12 text-center">
                  <!--<a href="Register"><?php echo $this->lang->line('login_register');?></a>-->
             </div>
        </fieldset>
        <?php echo form_close(); ?>
        <?php echo $this->session->flashdata('msg'); ?>
        </div>
   </div>
</div>
<?php
$this->view('Portal/footer');