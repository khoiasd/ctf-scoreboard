<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Auth extends CI_Controller
{
	private $event_id = 0;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}
	function login(){
		echo "<form action='".base_url('auth/login')."' method=POST><input name='key' autocomplete='off'/><input type=submit /></form>";
		
		if($this->input->method(True) === 'POST'){
			if($this->input->post('key') === SECRET_HASH){
				$this->session->set_userdata(['logged'=>True]);
				header('Location: ' . base_url('admin'));
				die();
			}else{
				echo "Access Deny!";
			}
		}
	}
	function logout(){
		$this->session->sess_destroy();
		header('Location: ' . base_url('auth/login'));
		die();
	}

}