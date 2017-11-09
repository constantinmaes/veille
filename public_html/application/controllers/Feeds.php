<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feeds extends CI_Controller {

	public function index()
	{
		$this->load->library('template');
		$this->load->model('feeds_model');
		$data['classe'] = $this->router->class;
		$data['feeds'] = $this->feeds_model->show();
		$data['feeds_to_save'] = $this->feeds_model->getContentFeed('https://www.google.com/alerts/feeds/02094895525641880152/13298696954654454427');
		$this->template->load('default','feeds_view',$data);
	}
}
