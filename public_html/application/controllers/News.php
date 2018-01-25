<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

	public function index()
	{
		$this->load->library('template');
		$this->load->model('news_model');
		$this->news_model->writeNews();
		$this->news_model->cleanDb();
		header('Location:./admin/all');
		//$data['classe'] = $this->router->class;
		//$data['news'] = $this->news_model->getAllNews();
		//$this->template->load('default','news_view',$data);
	}

	public function clean()
	{
		$this->load->model('news_model');
		$this->news_model->cleanDb();
	}
	public function publish()
	{
		$this->load->model('news_model');
		$id=$_POST['id_feed'];
		$this->news_model->publishNews($id);
	}
	public function discard()
	{
		$this->load->model('news_model');
		$id=$_POST['id_feed'];
		$this->news_model->discardNews($id);
	}
}
