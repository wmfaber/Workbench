<?php
require_once(getcwd().'/application/libraries/Handle/Handle_Module.php');

Class User_group extends Handle_Module {


	public function  ajax(){

		$action = $this->input->get('action');
		switch($action){
			case "delete_detail":
			$this->record   = $this->Handle_model->get_record_by_id($this->settings['module']['dataobject'].'_enrolment',$this->record_id);
			$this->data['record'] = $this->record;
			$this->data['role_id'] = $this->record_id;
			$this->data['module_name'] = $this->module;
			$this->data['nonce'] = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt'],$this->record['id']);
			$this->data['template'] = 'Portal/User_Group/user_group_ajax_confirm.php';
			$this->load->view('Portal/ajax_view',$this->data);
			break;
		}
		
		$action = $this->input->post('action');
		$post_nonce  = $this->input->post('nonce');
		$nonce 	= Handle_Module_Class::create_nonce($this->module,$this->config->config['salt']);
		$id 		= $this->input->post('id');
		$res = array();
		switch($action){
			case "delete_record_detail":
			$group_id = $this->input->post('group_id');
			//if($nonce == $post_nonce){
				$this->dataobject = $this->dataobject.'_enrolment';
				$this->db->get($this->dataobject);
				$this->db->where('id', $id);
				$check  = $this->db->delete($this->dataobject);
				$this->redirect_after_submit_list_detail($group_id );
			//}
			break;
			case "insert":
			$set = array();
			$set['user_id'] = $this->input->post('user_id');
			$set['group_id'] = $this->input->post('group_id');
			if($nonce == $post_nonce){
				$this->dataobject = $this->dataobject.'_enrolment';
				
				/*see if exists*/
				$val = $this->Handle_model->get_result_set($this->dataobject,$set);
				if(!$val){
					$status = $this->Handle_model->insert_record($this->dataobject,$set);
					if(!$status){
						$res['status'] = 'fail';
					}else{
						$res['status'] = 'succes';
						}
				}else{
					$res['status'] = 'fail';
				}
			}
			echo json_encode($res);
			break;
		}
		parent::ajax();
	}
	
	 public function redirect_after_submit_list_detail($id){
	 		header('Location:/'.$this->module.'/detail/'.$id);
	}

	public function get_enrolments(){
		$sql = 'select e.id,u.email,r.title
		from user_group_enrolment e
		left join user_login u on e.user_id = u.id
		left join user_group r on e.group_id = r.id
		where e.group_id = '.$this->record_id;
		$data =$this->Handle_model->sql($sql);
		return $data;
	}

	public function format_rows($row,$param = false){
		if($param == false){
			$row = parent::format_rows($row);
		}else{
			$row = $this->format_rows_detail($row);
		}
		return $row;
	}

	public function format_rows_detail($row){
		$return_row = array();
		$row['delete'] = '<a data-target="#MRweb_Modal" data-toggle="modal"  href = /'.$this->module.'/ajax/'.$row['id'].'?action=delete_detail><i class="glyphicon glyphicon-remove"></i></a>';
		$return_row = $row;
		return $return_row;
	}

	public function get_enrolment_table(){
		$this->load->library('table');
		$sort ='';
		$dropdown='';
		$rows_headers = array('id','user','role','delete');
		$this->table->set_heading($rows_headers);
		$rows_data = $this->get_enrolments();

		foreach($rows_data as $row){
			$table_row = $this->format_rows($row,true);
			unset($table_row['edit']);
			$this->table->add_row($table_row);
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

	public function detail(){
		//get all users
		$roles_table =  $this->dataobject;
		$user_table =  'user_login';

		$this->dataobject = $this->dataobject.'_enrolment';

		$arr =array('id'=>$this->record['id']);
		$res = $this->Handle_model->get_result_set($this->dataobject,$arr );

		$roles_res = $this->Handle_model->get_result_set($roles_table);
		$user_res = $this->Handle_model->get_result_set($user_table);

		$roles_format_array = Handle_Module_Class::format_array($roles_res,'id','title');
		$user_format_array = Handle_Module_Class::format_array($user_res,'id','email');

		$drop_roles = $this->generate_dropdown($roles_format_array,$this->record_id,false,'roles');
		$drop_user = $this->generate_dropdown($user_format_array,false,false,'user');

		$this->data['nonce'] = Handle_Module_Class::create_nonce($this->module,$this->config->config['salt']);
		$this->data['drop_roles'] = $drop_roles;
		$this->data['drop_user'] = $drop_user;
		$this->data['enrolments'] = $res;
		$this->data['roles'] = $roles_res;
		$this->data['is_enrolment']  = true;
		$this->data['users'] = $this->Handle_model->get_result_set($user_table );

		$this->data['template'] = 'Portal/User_Group/Detail.php';
		$this->get_enrolment_table();
	}
}