<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$data['title'] = 'home';
		//$this->load->view('welcome_message');
		$this->load->view('templates/default/header',$data);
		$this->load->view('templates/default/footer',$data);
	}
}
