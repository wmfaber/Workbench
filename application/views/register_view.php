<?php 
$this->view('Portal/header');
$this->view('Portal/menu');
?>
<div class="container main">
<div class="row">
        <div class="col-lg-6 col-sm-6 well">
        <?php 
        $attributes = array("class" => "form-horizontal", "id" => "Registreerform", "name" => "registreerform");
        Handle_Module_Class::form_open("Register/insert", $attributes, $form);
        ?>
        <fieldset>
             <legend><?php echo $this->lang->line('login_register');?></legend>
             <div class="form-group">
             <div class="row colbox">
             <div class="col-lg-4 col-sm-4">
             <label for="txt_mail" class="control-label"><?php echo $this->lang->line('login_email');?></label>
             </div>
             <div class="col-lg-8 col-sm-8">
                  <input class="form-control" id="txt_mail" name="email" placeholder="<?php echo $this->lang->line('login_email');?>" type="email" value="<?php echo set_value('email'); ?>" required/>
             </div>
             </div>
             </div>
             
             <div class="form-group">
             <div class="row colbox">
             <div class="col-lg-4 col-sm-4">
             <label for="txt_password" class="control-label"><?php echo $this->lang->line('login_password');?></label>
             </div>
             <div class="col-lg-8 col-sm-8">
                  <input class="form-control" id="txt_password" name="password" placeholder="<?php echo $this->lang->line('login_password');?>" type="password" value="<?php echo set_value('password'); ?>" required/>
                  <span class="text-danger"><?php echo form_error('password'); ?></span>
             </div>
             </div>
             </div>
                           
             <div class="form-group">
             <div class="row colbox">
             <div class="col-lg-4 col-sm-4">
             <label for="txt_password" class="control-label text-left"><?php echo $this->lang->line('login_password_confirm');?></label>
             </div>
             <div class="col-lg-8 col-sm-8">
                  <input class="form-control" id="txt_password" name="password-check" placeholder="<?php echo $this->lang->line('login_password');?>" type="password" value="<?php echo set_value('password'); ?>" required/>
                  <span class="text-danger"><?php echo form_error('password'); ?></span>
             </div>
             </div>
             </div> 
             
             <div class="form-group">
             <div class="col-lg-12 col-sm-12 text-left">
                  <input id="btn_login" name="btn_register" type="submit" class="btn btn-default" value="<?php echo $this->lang->line('login_register');?>" />
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