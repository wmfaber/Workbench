<div class="col-lg-6 col-sm-6 well">
<?php echo form_open_multipart(ucfirst($page_title).'/upload', array('class' => ''));?>
<fieldset>
   <legend>Upload</legend>
   <div class="form-group">
	   <div class="row colbox">
		   <div class="col-lg-4 col-sm-4 form-group">
		        <input type="file" multiple name="userfiles[]" size="20" />
		   </div>
	   </div>
   </div>
   <div class="form-group">
   	<div class="row colbox">
   		<div class="col-lg-4 col-sm-4 form-group">
		   <?php echo $vendor_dropdown; ?>
		 	 </div>	
   	</div>	
   </div>
   <div class="form-group">
	   <div class="col-lg-12 col-sm-12 text-center">
	   	<?php if ($status == '') { 
	   		echo "	<input type='hidden' name='nonce' value=". $nonce.">";
	   		?>
	        <input id="btn_login" name="upload" type="submit" class="btn btn-default" value="Upload">
	    <?php } ?>
	   </div>
   </div>
</fieldset>
<br/>
<?php echo $this->session->flashdata('msg'); ?>
</form>
</div>
