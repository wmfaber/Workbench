<?php 
class Portal extends CI_controller {	
		public function __construct() {
				$this->ci = &get_instance();
				$this->ci->load->database();
		}
		public function get_portal(){
		
			 
			$urlParts = explode('.', $_SERVER['HTTP_HOST']);
			$subdomain = $urlParts[0];
						
			
			$query = $this->ci->db->query("SELECT * FROM `portal` WHERE `title`='".$subdomain."'");
			$portal = array();
			$portal = $query->result_array();
			$portal = $portal[0];
			
			return $portal;
		}
}