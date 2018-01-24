<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sources extends CI_Controller {

	public function index(){
		$this->load->library('template');
		$this->load->model('sources_model');
		$this->sources_model->updateSources();
		$data['classe'] = $this->router->class;
		$this->template->load('default','sources_view',$data);
	}
}