<?php 
class Menu extends CI_controller {	
		public function __construct() {
				$this->ci = &get_instance();
				$this->ci->load->database();
				$this->ci->load->library('session');
				$this->ci->load->helper('language');
		}
		public function get_menu(){
			$modules = get_active_modules();
			
			$query = $this->ci->db->query("SELECT * FROM `page` WHERE `active`='Y'");
			$menu = array();
			$filterd = array();
			$result = $query->result_array();
			
			foreach($result as $key => $value){
				$filterd[$value['id']] = $value;
			}

			foreach ($filterd as $row)
			{
				if($row['title'] != 'Login')
				{
					if($row['page_id'] == 0)
					{
						if($row['title'] != 'Home'){
							if($row['require_login']== 'N'|| $this->ci->session->userdata('username') != null) // 11-5-2015 12:39:04 MF
							{
							  if($row['menu_item'] == 'Y'){
										$menu[$row['id']][$this->ci->config->item('base_url').$row['desc']] = $row['title'];
								}
							}
						}
					}
					else
					{
						if( $row['require_login'] == 'N'|| $this->ci->session->userdata('username') != null)
						{
							$menu[$row['page_id']][$this->ci->config->item('base_url').$row['desc']] = $row['title'];
						}
					}
				}
				else
				{
					if($this->ci->session->userdata('username') != null)
					{
						$menu[$row['id']][$this->ci->config->item('base_url').$row['desc']] = 'Logout';
					}
					else
					{
						$menu[$row['id']][$this->ci->config->item('base_url').$row['desc']] = $row['title'];

					}
				}
			} 
			return $menu;
		}
}