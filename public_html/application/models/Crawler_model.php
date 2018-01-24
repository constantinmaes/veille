<?php
class Crawler_model extends CI_model {
	public function __construct()
        {
                $this->load->database();
                $this->load->model('feeds_model');

        }
   	
    public function getKeywords(){
   		$keywords = array();
   		$results = $this->db->get('keywords');
   		foreach($results->result() as $row){
   			$keywords[] = array(
   				'id' => $row->id,
   				'word' => $row->word,
   			);
   		}
   		return $keywords;
   	}
   	
    public function crawlingUrlConstruct($keyword){
      $url = 'https://www.google.be/search?q='.$keyword.'&tbm=nws&tbs=qdr:h,sbd:1';
      return $url;
    }

    public function crawlOnKeyword($keyword){
      $url = $this->crawlingUrlConstruct($keyword);
      $content = '';
      $fp=fopen($url,'r');
      while(!feof($fp)){
        $content .= fgets($fp,1024);
      }
      fclose($fp);
      echo $content;
   	}

    

    public function crawlAll(){
      $keywords = $this->getKeywords();
      foreach($keywords as $keyword){
        $st = $keyword['word'];

      }
    }

}