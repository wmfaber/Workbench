<?php
require_once(getcwd().'/application/libraries/Handle/Handle_Module.php');
class Home extends Handle_Module {
		
		public function index()
		{
			$data['page_title'] = 'home';
			$data['action'] = "info";
			$data['portal'] = $this->portal;
			$data['menu']   = $this->menu->get_menu();
			
			$this->load->view('Portal/index', $data);
		}
}