<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(getcwd().'/application/libraries/Handle/Handle_Module.php');
require_once(getcwd().'/application/libraries/Handle/Handle_Module.class.php');
class Register extends Handle_Module {
	
     public function __construct()
     {
          parent::__construct();
          $this->load->library('session');
          $this->load->helper('form');
          $this->load->helper('url');
          $this->load->helper('html');
          $this->load->database();
          $this->load->library('form_validation');
          //load the login model
          $this->load->model('login_model');
          
          $this->load->library('menu');
					$this->data['menu']= $this->menu->get_menu();
							
					$this->load->library('portal');
					$this->data['portal'] = $this->portal;
					$this->data['page_title'] = $this->module;
     }
     
     public function form()
     {
     			
     		if(!$this->session->userdata('username'))
     		{
          //set validations
          $this->form_validation->set_rules("password", "Password", "trim|required");
          $this->add_post_action(array('update_verify',	'mail_link'));
          $this->data['form'] = $this->form;
         

          if ($this->form_validation->run() == FALSE)
          {
               //validation fails
               $this->load->view('register_view',$this->data);
          }
          else
          {
          	
          }
        }
        else
        {
        		$this->load->view('logout_view',$this->data);
        }
     }
     
     

     
     	public function insert($set = null,$redir_url = null)
     	{
				//get the posted values
        $mail = $this->input->post("email");
        $password = $this->input->post("password");
			  $check = $this->input->post("password-check");
			  if($password == $check)
			  {
				  //validation succeeds
	        $valid = $this->validate($mail,$password);
	        if($valid)
	        {
	        	$set = array();
	        	$set = $this->input->post();
	        	$set['password'] = md5($set['password']);
	        	$set['active'] = "N";
	        	unset($set['btn_register']);
	        	unset($set['password-check']);
	        	$redir_url = "Login";
	        	$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please verify your account in your mail!</div>');
	   				parent::insert($set, $redir_url);
	   			}
	   			else
	   			{
	   				$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Email already exist!</div>');
            redirect('Register');
	   			}
	   		}
	   		else
	   		{
	   			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Password doesn\'t comapre!</div>');
		   		redirect('Register');	
		   	}
    	}
     
     public function validate($mail,$password)
     {
     		$email = $this->db->query("SELECT * FROM `user_login` WHERE `email`='" . $mail . "'");

  		if ($email->num_rows() > 0)
     		{
     			return false;
     		}
     		else
     		{
     			return true;
     		}
     }
     
     public function verify()
     {     		
				$resultobject = $this->db->get_where($this->dataobject, array('verifylink' => $this->record_id))->result();
     		
     		$this->record_id = $resultobject[0]->id;
     		     		
     		$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Thanks for verifying your account!</div>');
     		$this->update(array('active'=>'Y'),'Login');
     }
     
     
     public function update_verify()	
     {
     		$this->Handle_model->update_record($this->dataobject, $this->record_id, array('verifylink'=> md5($this->portal['title'].$this->record_id) ));
     }
     
     
     public function mail_link($mail=null) 
     {    
     		$this->record = $this->Handle_model->get_record_by_id($this->dataobject,$this->record_id);
				
     		$this->load->library('email');

				$this->email->from($this->portal['email'], $this->portal['title']);
				$this->email->to($this->record['email']); 
				
				$this->data['link'] = $this->config->item('base_url').'Register/Verify/'.$this->record['verifylink'];
				$this->data['title'] = $this->portal['title'];
				ob_start();
				include(getcwd().'/application/views/Portal/portal_mail.php');
				$message = ob_get_clean();

				$this->email->subject('Verify your '.$this->portal['title'.' acount']);
				
				$this->email->message($message);
				
				$this->email->send();
     }
}?>
