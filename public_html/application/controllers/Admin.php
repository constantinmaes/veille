<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index()
	{
		$this->load->library('template');
		$this->load->model('news_model');
		$data['classe'] = $this->router->class;
		$data['news'] = $this->news_model->getAllNews();
		$this->template->load('admin','waitlist_view',$data);
	}

	public function keywords(){
		$this->load->library('template');
		$this->load->model('news_model');
		if(isset($_POST['bSaveKeyword'])){
			$word = strip_tags($_POST['word']);
			$rss_url = strip_tags($_POST['rss_url']);
			$this->news_model->createKeyword($word,$rss_url);
		}
		$data['keywords']=$this->news_model->getAllKeywords();
		$this->template->load('admin','keywords_view',$data);
	}

	public function waitlist(){
		$this->load->library('template');
		$this->load->model('news_model');
		$data['classe'] = $this->router->class;
		$data['news'] = $this->news_model->getAllNews();
		$this->template->load('admin','waitlist_view',$data);
	}

	public function all()
	{
		$this->load->library('template');
		$this->load->model('news_model');
		$data['classe'] = $this->router->class;
		$data['news'] = $this->news_model->getAllNews();
		$this->template->load('admin','news_view',$data);
	}
}
