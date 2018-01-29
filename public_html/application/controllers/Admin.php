<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index()
	{
		$this->load->library('template');
		$this->load->model('user_model');
		$this->load->model('news_model');
		$data['classe'] = $this->router->class;
		if(isset($_POST['login']) && isset($_POST['password'])){
			$login = strip_tags($_POST['login']);
			$password=strip_tags($_POST['password']);
			$this->user_model->login($login,$password);
			if($_SESSION['logged_in']){
				header('Location:./index.php/admin/all');
			}
			else{
				header('Location:./index.php/admin');
			}
		}
		else{
			$this->template->load('login','login_view');
		}
		//$data['news'] = $this->news_model->getAllNews();
		//$this->template->load('admin','waitlist_view',$data);
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

	public function all_refresh()
	{
		$this->load->library('template');
		$this->load->model('news_model');
		$data['classe'] = $this->router->class;
		$news = $this->news_model->getAllNews();
		$table = '';
		foreach($news as $article){
			$table .= '<tr>';
			$table .= '<td>'.$article['id_feed'].'</td>';
			$table .= '<td>'.$article['title'].'</td>';
			$table .= '<td><a href="'.$article['url'].'" target="_blank">Lien</a></td>';
			$table .= '<td>'.$article['retrieval_date'].'</td>';
			$table .= '</tr>';
		}
		echo $table;
	}

	public function add()
	{
		$this->load->library('template');
		$this->load->model('news_model');
		$data['classe'] = $this->router->class;
		if(!isset($_POST['bAdd'])){
			$data['keywords']=$this->news_model->getAllKeywords();
			$this->template->load('admin','add_view',$data);
		}
		else{
			$added = $this->news_model->addNews();
			if($added){
				header('Location:./all');
			}
			else{
				header('Location:./add');
			}
		}
	}
	public function analyse()
	{
		$this->load->library('template');
		$this->load->model('news_model');
		$data['classe'] = $this->router->class;
		$data['results'] = $this->news_model->analyseAll();
		$this->template->load('admin','analyse_view',$data);
	}
}
