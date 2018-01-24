<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	public function index()
	{
		$this->load->library('template');
		$this->load->model('blog_model');
		$data['classe'] = $this->router->class;
		$data['posts'] = $this->blog_model->getAllPosts();
		$this->template->load('blog','home_view',$data);
	}

}
