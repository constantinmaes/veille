<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            $this->load->helper('url_helper');
            $this->load->library('session');
            
        }


	public function index()
	{	
		if(!isset($_POST['login']) or !isset($_POST['password'])){
			$this->load->library('template');
			$this->load->view('login_view');
		}
		else{
			$this->load->model('user_model');
			$login = $_POST['login'];
			$password = $_POST['password'];
			$this->user_model->login($login,$password);
			$url_admin = 'http://'.base_url().'index.php/admin';
			redirect($url_admin);
		}
	}
	public function logout()
	{
		session_destroy();
		$url_admin = 'http://'.base_url().'index.php/admin';
		redirect($url_admin);
	}
}
