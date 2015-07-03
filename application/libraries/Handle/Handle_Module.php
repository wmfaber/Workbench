<?php
/*switch*/
error_reporting(0);
require_once('Handle_Module.class.php');
Class Handle_Module extends CI_Controller {
	public $medule_require_login;
	public $module_name;
	public $default_method;
	public $record_id;
	public $method;
	public $module;
	public $dataobject;
	public $form;
	public $portal;
	public $menu;
	public $data;
	public $ajax = false;

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('language');
		$this->load->library('cart');
		
		$this->record_id  = $this->uri->segment(3);
		$this->method 		= $this->uri->segment(2);
		$this->module 		= ucfirst($this->uri->segment(1));
		if(strtolower($this->method) == 'ajax' or $this->input->get('ajax') or $this->input->post('ajax')){
			$this->ajax = true;
			$this->ajax_form_array = array('data-async'=>true);
		}
		$this->data['method'] = $this->method;
		$this->data['template'] = null;

		$this->load->library('menu');
		$this->data['menu'] = $this->menu->get_menu();

		$this->data['status'] = $this->input->get('status') ? $this->input->get('status') : "";

		$this->load->library('portal');
		$this->portal = $this->portal->get_portal();
		$this->start_module();
		

		if($this->record_id){
			$this->record   = $this->Handle_model->get_record_by_id($this->settings['module']['dataobject'],$this->record_id);
		}
		
		if(!isset($this->default_method)){
			$this->default_method = 'view';
		}

		if($this->method and Handle_Module_Class::validate_method($this->method) and strtolower($this->method) != 'ajax'){
			//if($this->method and strtolower($this->method) != 'ajax'){
			$this->{$this->method}();
		}else{
			//$this->{$this->default_method}();
		}
		if(isset($this->settings['module']['side'])){
			$this->data['side'] = $this->settings['module']['side'];
		}else{
			$this->data['side'] = true;
		}

		$this->download_record = isset($this->settings['module']['download_record']) ? $this->settings['module']['download_record'] : false;

	}

	public function start_module(){
		$active_modules = get_active_modules();

		if(isset($active_modules[$this->module])){
			$this->settings = $active_modules[$this->module];
					
			if($this->settings['module']['require_login'] != true || $this->session->userdata('username') != null)
			{
				$this->dataobject = strtolower($this->settings['module']['dataobject']);
			}
			else
			{
				redirect('login');
			}
		}
	}

	public function ajax(){

		$action = $this->input->get('action');
		switch($action){
			case "delete":
			$this->record   = $this->Handle_model->get_record_by_id($this->settings['module']['dataobject'],$this->record_id);
			$this->data['record'] = $this->record;
			$this->data['module_name'] = $this->module;
			$this->data['nonce'] = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$this->record['id']);
			$this->data['template'] = 'Portal/portal_ajax_confirm.php';
			$this->load->view('Portal/ajax_view',$this->data);
			break;
			case "add":
			case "edit":
			$this->form();
			$this->data['nonce'] = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$this->record['id']);
			$this->data['template'] ='Portal/portal_form.php';
			$this->load->view('Portal/ajax_view',$this->data);
			break;
			case "download":
			$this->record   = $this->Handle_model->get_record_by_id($this->settings['module']['dataobject'],$this->record_id);
			$this->data['modalaction'] = $this->input->get('modalaction');
			$this->data['record'] = $this->record;
			//$this->data['nonce'] = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$this->record['id']);
			$this->data['nonce'] = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt']);
			$this->data['module_name'] = $this->module;
			$this->data['template'] ='Portal/portal_ajax_download.php';
			$this->load->view('Portal/ajax_view',$this->data);
			break;
		}

		$action = $this->input->post('action');
		$id 		= $this->input->post('id');
		$post_nonce  = $this->input->post('nonce');
		$nonce 	= Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$id);
		switch($action){
			case "delete_record":
			if($nonce == $post_nonce){
				$this->db->get($this->dataobject);
				$this->db->where('id', $id);
				$check  = $this->db->delete($this->dataobject);
				$this->redirect_after_submit_list();
			}else{
				redirect('/404');
			}
			break;
			case "download_record":
			if($nonce == $post_nonce){
				$this->db->select($this->settings['form']);
				$query = $this->db->get_where($this->dataobject, array('id' => $id));

				$this->export_to_csv($query->result_array());
			}else{
				redirect('/404');
			}
			break;
			case "download_all":
			if($nonce == $post_nonce){
					$data = $this->get_list();
					$this->export_to_csv($data);
			}else{
				redirect('/404');
			}
			break;
		}

	}
	
	public function get_list_filter(){
		$get_param = $this->input->get();
		$headers = $this->get_headers_table();
		if($get_param)
		{
			$res = array();
		}else{
			return false;
		}
		foreach($get_param as $param => $value){
			if(in_array( $param,$headers)){
				$res[array_search($param,$headers)] = $value;
			}
		}
		return $res;
	}
	
	public function detail(){
		$this->data['record'] = $this->record;
		$this->data['template'] = 'Portal/portal_detail.php';
		

		if($this->ajax == false){
			$this->create_data();
		}
	}
	
	public function get_list(){
		$filter = $this->get_list_filter();
		$this->db->select($this->get_headers_table());
		
		foreach($filter as $key =>$value)
		{
			$this->db->where($key,$value);
		}
		$do = $this->db->get($this->dataobject);
		
		foreach($do->result_array() as $data){
			$rows_data[] = $data;
		}
		return $rows_data;
	}

	public function get_headers_table(){
		$list = $this->settings['list'];
		$select = array();
		foreach($list as $key => $value){
			if($value == true){
				array_push($select, $key);
			}
		}
		array_push($select,''); //edit
		array_push($select,''); // delete
		if($this->download_record) { array_push($select,''); }
		return $select;
	}


	public function view() {
		$this->load->library('table');
		$sort ='';
		$dropdown='';
		$rows_headers = $this->get_headers_table();
		$this->table->set_heading($rows_headers);
		$rows_data = $this->get_list();
		foreach($rows_data as $row){
			$table_row = $this->format_rows($row);
			$this->table->add_row($table_row );
		}
		
		$this->data['action'] = 'view';
		$this->data['add_button'] = $this->add_button();

		$tmpl = array ( 'table_open'  => '<table class="table table-hover" id = "view_table" >' );
		$this->table->set_template($tmpl);
			if(isset($this->settings['list_sort'])){
				foreach($this->settings['list_sort'] as $field =>$label){
					$sort.= $this->generate_sort(explode(',',$this->settings['list_sort_values'][$field]), $field,$label);
				}
			}
		if(isset($this->settings['list_settings']['delimitter'])){
			$limit = $this->input->get('loadedentrys') ? $this->input->get('loadedentrys') : '500';
			$dropdown = $this->generate_dropdown(array('500','1000','1500','2000','3000','4000','5000','all'), $limit,true);
		}
		$this->data['view'] =$sort.$dropdown . "<div class='table_container' style=\"margin-top:15px\">". $this->table->generate().'</div>';
		if($this->ajax == false){
			$this->create_data();
		}
	}
	
	public function add_button(){
		$add_buttons  = '';
		if(isset($this->settings['button'])){
			if(isset($this->settings['button']['add']) &&  $this->settings['button']['add'] == 1){
				$add_buttons  .= '<a data-target="#MRweb_Modal" class="btn btn-primary" data-toggle="modal" href = "'.$this->config->config['base_url'].$this->module.'/ajax?action=add&ajax=1 ">Toevoegen</a>';
			}
			if(isset($this->settings['button']['download']) && $this->settings['button']['download']==1){
					$add_buttons  .= '<a data-target="#MRweb_Modal" class="btn btn-primary" data-toggle="modal" href = "'.$this->config->config['base_url'].$this->module.'/ajax?action=download&modalaction=download_all">Download</a>';
			}
		}else{
			$add_buttons  .= '<a data-target="#MRweb_Modal" class="btn btn-primary" data-toggle="modal" href = "'.$this->config->config['base_url'].$this->module.'/ajax?action=add&ajax=1 ">Toevoegen</a>';
			$add_buttons  .= '<a data-target="#MRweb_Modal" class="btn btn-primary" data-toggle="modal" href = "'.$this->config->config['base_url'].$this->module.'/ajax?action=download&modalaction=download_all">Download</a>';
		}
		return $add_buttons;
	}
	
	public function generate_sort($data,$field,$label = 'Sorteer:')
	{
		$sort =  "<div class='form-group' style=\"float:left;margin-left:15px\">";
		$sort .=  "<label for='sort_".$field."'>".$label."</label>";
		$sort .= "<select name='sort_".$field."'  class='mr_sort form-control' id='sort_".$field."'>";		
		foreach($data as $value)
		{
			$collection = explode('.',$value);
			$value = $collection[0];
			$key   = $collection[1];
			if($this->input->get($field) == $key and $this->input->get($field)) {
				$sort .= "<option value='".$key."' selected>".$value."</option>";
			}else{
				$sort .= "<option value='".$key."'>".$value."</option>";
			}
		}
		$sort .= "</select>";
		$sort .= "</div>";
		return $sort ;
	}
	
		public function generate_dropdown($data, $current,$loaded_ent = false,$class = null){
		$dropdown =  "<div class='form-group' style=\"float:left;margin-left:15px\">";
		if($loaded_ent){		
		$dropdown .= "<label for='loaded-entrys'>Aantal geladen regels</label><select name='loaded-entrys' class='mr_limit form-control' id='loadedentrys'>";
		}else{
			$dropdown .= "<label for='loaded-entrys'>Aantal geladen regels</label>
										<select  class='".$class."_select form-control'>";
		}
		foreach($data as $key => $value)
		{
			if($current == $value or $current == $key) {
				$dropdown .= "<option value='".$key."' selected>".$value."</option>";
			}else{
				$dropdown .= "<option value='".$key."'>".$value."</option>";
			}
		}
		$dropdown .= "</select>";
		$dropdown .= "</div>";
		return $dropdown;
	}


	public function form(){
		$this->form = new stdclass;
		$this->ajax_form_array['enctype'] = "multipart/form-data";
		if($this->record_id){
			if($this->ajax){
				$this->form->open = Handle_Module_Class::form_open($this->module.'/update/'.$this->record_id.'?ajax=1',$this->ajax_form_array);
			}else{
				$this->form->open = Handle_Module_Class::form_open($this->module.'/update/'.$this->record_id);
			}
		}	else{
			if($this->ajax){
				$this->form->open = Handle_Module_Class::form_open($this->module.'/insert?ajax=1', $this->ajax_form_array );
			}else{
				$this->form->open = 	Handle_Module_Class::form_open($this->module.'/insert');
			}
		}
		foreach($this->settings['form'] as $field => $show){
			if($show){
				$this->set_field($field);
			}
		}
		
		$this->add_form_input();

		$this->form->submit = form_submit(array('id'=>'submit','value'=>'Opslaan','class'=>'btn'));
		$this->form->close = form_close();

		$this->data['form'] = $this->form;
		if($this->ajax == false){
			$this->data['action'] = 'form';
			$this->create_data();
		}
	}
	
	public function add_form_input($field){
		return true;
	}
	
	public function set_form_input($field){
		foreach($field as $field_name => $field_value){
			if(isset($field_value['value'])){
				 $this->record[$field_name] = $field_value['value']; 
			}
			$this->set_field($field_name, $field_value['type']);
		}
	}

	public function set_field($field,$type = null)
	{
				/*if($type == null){
			$type = get_database_to_formfield($field);
		}*/
		
		if(isset($this->settings['form_type'][$field])){
			$type = $this->settings['form_type'][$field];
		}else{
			$type = 'text';
		}
			
		switch($type){
			case "date":
			$this->form->element[$field]['label'] = form_label($field, $field, array('class'=>'label label-default form-vert-label'));
			if($this->record_id){
				$datetime = date('d-m-Y',strtotime($this->record[$field]));
				$this->form->element[$field]['input'] = form_input(array('id'=>$field,'name'=>$field,'type'=>'text', 'class'=>'form-control input-xlarge datepicker', 'value'=>$datetime));
			}else{
				$this->form->element[$field]['input'] =	form_input(array('id'=>$field,'name'=>$field,'type'=>'text', 'class'=>'form-control input-xlarge datepicker' ));
			}
			break;
			case "file":
			$this->form->element[$field]['label'] = form_label($field, $field, array('class'=>'label label-default form-vert-label'));
			if($this->record_id){
				$this->form->element[$field]['input'] = $this->form_file($field, $this->record[$field]);
			}else{
				$this->form->element[$field]['input'] =	$this->form_file($field);
			}
			$this->add_post_action(array(0=>'upload'));
			break;
			case "select":
			$this->form->element[$field]['label'] = form_label($field, $field, array('class'=>'label label-default form-vert-label'));
			if($this->record_id){
				$this->form->element[$field]['input'] = $this->get_drop_down($field, $this->record[$field]);
			}else{
				$this->form->element[$field]['input'] =	$this->get_drop_down($field);
			}
			break;
			case "checkbox":
			$this->form->element[$field]['label'] 						=  form_label($field,$field, array('class'=>'label label-default form-vert-label'));
			if($this->record_id){
				$this->form->element[$field]['input'] 					=	 form_input(array('id'=>$field,'name'=>$field,'type'=>'hidden', 'class'=>'form-control input-xlarge','value'=>$this->record[$field]));
			}else{
				$this->form->element[$field]['input'] 					=	 form_input(array('id'=>$field,'name'=>$field,'type'=>'hidden', 'class'=>'form-control input-xlarge'));
			}
			$this->form->element[$field.'_checkbox']['input'] =	 form_input(array('id'=>$field.'_checkbox','name'=>$field.'_checkbox','type'=>$type, 'class'=>'mrcheckbox form-control input-xlarge change_checkbox'));
			break;
			case "textarea":
			$this->form->element[$field]['label'] = form_label($field,$field, array('class'=>'label label-default form-vert-label'));
			if($this->record_id){
				$this->form->element[$field]['input'] =  form_textarea(array('id'=>'wysi','name'=>$field, 'class'=>'form-control'), $this->record[$field]);
			}else{
				$this->form->element[$field]['input'] =	 form_textarea(array('id'=>'wysi','name'=>$field, 'class'=>'form-control'));
			}
			break;
			case "money":
			$this->form->element[$field]['label'] = form_label($field,$field, array('class'=>'label label-default form-vert-label'));
			if($this->record_id){
				$this->form->element[$field]['input'] =  form_input(array('id'=>$field,'name'=>$field,'value'=>$this->record[$field],'type'=>'text', 'class'=>'form-control input-xlarge money'));
			}else{
				$this->form->element[$field]['input'] =	 form_input(array('id'=>$field,'name'=>$field,'type'=>'text', 'class'=>'form-control input-xlarge money'));
			}
			break;
			default:
			$this->form->element[$field]['label'] = form_label($field,$field, array('class'=>'label label-default form-vert-label'));
			if($this->record_id || $field == "parent_id" || $field == "cursus_id" || $field == "key" || $field == "type"){
				$this->form->element[$field]['input'] =  form_input(array('id'=>$field,'name'=>$field,'value'=>$this->record[$field],'type'=>$type, 'class'=>'form-control input-xlarge'));
			}else{
				$this->form->element[$field]['input'] =	 form_input(array('id'=>$field,'name'=>$field,'type'=>$type, 'class'=>'form-control input-xlarge'));
			}
			break;
		}
		$this->form->element[$field]['type'] =	 $type;
	}

	public function add_post_action($array){
		foreach($array as $key => $value){
			$this->form->element['post_action['.$key.']']['input'] = form_input(array('id'=>'post_action['.$key.']','name'=>'post_action['.$key.']','value'=>$value,'type'=>'hidden', 'class'=>'form-control input-xlarge'));
			$this->form->element['post_action['.$key.']']['type'] =	'hidden';
		}
	}

	public function update($set = null, $redir_url = null){
		$post_nonce = $this->input->post('nonce');
		$nonce = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$this->record_id);
		if($post_nonce != $nonce){
			redirect('/404');
		}
		if($set == null){
			$set  = $this->input->post();
		}
		
		foreach ($set as $key => $value){
			if(strtotime($value) !== false && $value != "N" && $value != "Y") {
   			// valid date/time
				$set[$key] = date('Y/m/d',strtotime($value));
			}
		}

		if($this->ajax){
			$this->record_id = $this->Handle_model->update_record($this->dataobject,$this->record_id,$set,'ajax');
		}else{
			$this->record_id = $this->Handle_model->update_record($this->dataobject,$this->record_id,$set, $redir_url);
		}
		$this->proces_post_action();
		if($redir_url != null){ redirect($redir_url);}
		if($this->ajax){
			$this->redirect_after_submit_list();
		}else{
			$this->redirect_after_submit($id);
		}
	}

	public function insert($set = null, $redir_url = null){
		$post_nonce = $this->input->post('nonce');
		$nonce = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$this->record_id);
		if($post_nonce != $nonce){
			redirect('/404');
		}
		if($set == null){
			$set  =$this->input->post();
		}
			
		foreach ($set as $key => $value){
			if(strtotime($value) !== false && $value != "N" && $value != "Y") {
   			// valid date/time
					$set[$key] = date('Y/m/d',strtotime($value));
			}
		}
						
		if($this->ajax){
			$this->record_id = $this->Handle_model->insert_record($this->dataobject,$set,'ajax');
		}else{
			$this->record_id = $this->Handle_model->insert_record($this->dataobject,$set,$redir_url);
		}
		$this->proces_post_action();
		if($redir_url != null){
			redirect($redir_url);
		}
		if($this->ajax){
			$this->redirect_after_submit_list();
		}else{
			$this->redirect_after_submit($id);
		}
	}

	public function create_data(){
		$data =  $this->data;
		$data['portal'] =	$this->portal;
		$data['page_title'] = $this->module;
		$data['module'] = $this->data;
		$data['module_name'] = $this->module;
		if(isset($this->record)){
			$data['record'] = $this->record;
		}

		$this->load->view('Portal/index',$data);
	}

	public function format_rows($row){
		if(isset($this->settings['list_config']['detail'])|| $this->settings['list_config']['detail'] == true)
		{
			$row['title'] = '<a href=/'.$this->module.'/detail/'.$row['id'].'>'.$row['title'].' </a>';
		}
		$return_row = array();
		$row['edit'] = '<a data-target="#MRweb_Modal" data-toggle="modal" href = /'.$this->module.'/ajax/'.$row['id'].'?action=edit&id='.$row['id'].'><i class="glyphicon glyphicon-edit"></i></a>';
		$row['delete'] = '<a data-target="#MRweb_Modal" data-toggle="modal"  href = /'.$this->module.'/ajax/'.$row['id'].'?action=delete><i class="glyphicon glyphicon-remove"></i></a>';
		if($this->download_record){
			$row['download'] = '<a data-target="#MRweb_Modal" data-toggle="modal"  href = /'.$this->module.'/ajax/'.$row['id'].'?action=download><i class="glyphicon glyphicon-download"></i></a>';
		}
		$return_row = $row;
		return $return_row;
	}

	public function upload_form(){
		$this->data['status'] = $this->input->get('status');
		if($this->settings['module']['upload'] == true)
		{
			$this->data['action'] = "upload";
			$this->data['nonce'] = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$this->record['id']);
			$this->create_data();
		}
		else
		{
			redirect($this->module);
		}
	}

	public function upload() {
		if($this->settings['module']['upload'] == true)
		{
			if (!is_dir($this->settings['upload']['upload_path']))
			{
				mkdir($this->settings['upload']['upload_path']);
			}
						
			$this->settings['upload']['multi'] = 'all';
			$this->load->library('upload', $this->settings['upload']);
			$this->upload->do_multi_upload('userfiles');
			
			$data = $this->upload->get_multi_upload_data();
						
			if($data == null){
				$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">'.$this->lang->line('upload_faild').'</div>');
				redirect($this->module);
			}
			foreach($data as $set){
				
				if ($set['file_ext'] == ".txt"){
					$set = $this->txt_to_csv($set);
				}
				
				
				$this->import( $this->import_array($set) );
			}
			
			if($this->session->flashdata('msg') == '') {
				$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">'.$this->lang->line('upload_succes').'</div>');
			}
			redirect($this->module);
		
	}
	else{
		return false;
	}
	
	}
	
	public function import_array($set){
		return $import = array('upload_data' => $set, 'file_name' => 'file_name');
	}

	public function import(){
		return true;
	}

	public function redirect_after_submit($id){
		header('Location:/'.$this->module.'/edit/'.$id);
	}

	public function redirect_after_submit_list($id){
		header('Location:/'.$this->module.'/');
	}

	public function proces_post_action(){
		$set = $this->input->post('post_action');
		foreach($set as $action){
			$this->{$action}();
		}
	}

	public function export_to_csv($data, $filename = "export.csv", $delimiter=";") {
		ob_end_clean();
		$filePath = 'php://output';
		header('Content-Encoding: UTF-8');
		header('Content-Type: application/csv; charset=UTF-8');
		header('Content-Disposition: attachement; filename="'.$filename.'";');
		$f = fopen($filePath, 'w');
		echo "\xEF\xBB\xBF"; // UTF-8 BOM

		$header = array();
		// GET CURRENT HEADERS
		foreach ($data[0] as $key => $value) {
			array_push($header,$key);
		}
		fputcsv($f, $header, $delimiter);
		//unset($data['headers']);
		foreach ($data as $line) {
			fputcsv($f, $line, $delimiter);
		}
		fclose($f);
	}
	
		
	public function txt_to_csv($set) { 
		$text = file($set['full_path']);
		
		$content = array();
		foreach ($text as $line) {
			$tab = preg_split("/[\t]/", $line);
			array_push($content,$tab);
		}
		$new = array();
		foreach ($content as $array) {
			$res = array_map("utf8_encode", $array );
			array_push($new,$res);
		}
			
		
		header('Content-Encoding: UTF-8');
		$origname = str_replace(".txt",".csv",$set['orig_name']);
		$filePath = $set['file_path'];
		$filename = $set['raw_name'].".csv";
				
		$csv_handler = fopen ($filePath.$filename,'w');	
		fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($csv_handler, array_keys($new['0']));
		foreach ($new as $value) {
			fputcsv($csv_handler, $value);
		}	
		fclose ($csv_handler);
		
		$set['orig_name'] = $origname;
		$set['file_name'] = $filename;
		$set['full_path'] = $filePath.$filename;
		return $set;
	}
	
	public function get_drop_down($name, $default=null){
		$this->db->select('id');
		$this->db->where('title', $name);
		$res = $this->db->get('item')->result();
		$id = $id[0]->id;
		
		$this->db->where('parent_id', $name);
		$this->db->where('type', 'option');
		$result = $this->db->get('item')->result_array();
		
		$dropdown = "<select name='".$name."' class='form-control input-xlarge'>";
		foreach($result as $value)
		{
			if($default != null && $default == $value['value'])
				$dropdown .= "<option value='".$value['value']."' selected>".$value['value']."</option>";
			else
				$dropdown .= "<option value='".$value['value']."' >".$value['value']."</option>";
		}
		$dropdown .= "</select>";
		return $dropdown;
	}
	
	public function form_textarea($field, $default=""){
		return "<textarea class=\"".$field['class']."\" rows=\"5\" name=\"".$field['name']."\" id=\"".$field['id']."\">".$default."</textarea>";
	}
	
	public function form_file($field, $default=""){
		return "<input type=\"file\" class=\"form-control input-xlarge\" multiple name=\"userfiles[]\" id=\"".$field."\" value=\"".$default."\"/>";
	}
}