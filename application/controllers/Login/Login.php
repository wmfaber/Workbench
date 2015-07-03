<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(getcwd().'/application/libraries/Handle/Handle_Module.php');
class login extends Handle_Module
{

     public function __construct(){
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

					if($this->input->get('status') == 'logout')
						$this->logout();
     }

     public function index()
     {
     			
     		if(!$this->session->userdata('username'))
     		{
          //get the posted values
          $username = $this->input->post("txt_username");
          $password = $this->input->post("txt_password");

          //set validations
          $this->form_validation->set_rules("txt_username", "Username", "trim|required");
          $this->form_validation->set_rules("txt_password", "Password", "trim|required");

          if ($this->form_validation->run() == FALSE)
          {
               //validation fails
               
               $this->load->view('login_view',$this->data);
          }
          else
          {
               //validation succeeds
               if ($this->input->post('btn_login') == "Login")
               {
                    //check if username and password is correct
                    $usr_result = $this->login_model->get_user($username, $password);
                    if ($usr_result > 0 && !empty($usr_result)) //active user record is present
                    {
                    		 //get user groups
                    		 $this->db->select('group_id');
                    		 $this->db->where('user_id',$usr_result[0]->id);
                    		 $res = $this->db->get('user_group_enrolment')->result();
                    		 $groups = array();
                    		 foreach($res as $group){
                    		 		array_push($groups, $group->group_id);
                    		 }
                    		 
                         //set the session variables
                         $sessiondata = array(
                              'username' => $username,
                              'loginuser' => TRUE,
                              'enrolment' => $groups
                         );
                         $this->session->set_userdata($sessiondata);
                         redirect();
                    }
                    else
                    {
                         $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">'.$this->lang->line('login_invalid_username').'</div>');
                         redirect('login');
                    }
               }
               else
               {
                    redirect('login');
               }
          }
        }
        else
        {
        		$this->load->view('logout_view',$this->data);
        }
     }
     
     public function logout()
     {
     		$this->session->sess_destroy();
 				redirect();
     }
}?>