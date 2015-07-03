<?php 
class Portal extends CI_controller {	
		public function __construct(){
			$this->ci = &get_instance();
			 
			$urlParts = explode('.', $_SERVER['HTTP_HOST']);
			$subdomain = $urlParts[0];
						
			$this->load->database();
			$query = $this->db->query("SELECT * FROM `portal` WHERE `title`='".$subdomain."'");
			$portal = array();
			$portal = $query->result_array();
			
			return $portal;
		}
}