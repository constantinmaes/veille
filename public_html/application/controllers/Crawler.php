<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crawler extends CI_Controller {

  public function crawl()
  {
    $this->load->library('template');
    $this->load->model('crawler_model');
    $this->crawler_model->crawlOnKeyword('bitcoin');
  }
}
