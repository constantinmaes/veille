<?php
class Sources_model extends CI_model {
	public function __construct()
        {
                $this->load->database();
                $this->load->model('feeds_model');

        }
   	public function getAll(){
   		$results = array();
   		$sources = $this->db->get('sources');
   		foreach($sources->result() as $row){
   			$results[] = array(
   				'id_source' => $row->id,
   				'url' => $row->url,
   			);
   		}
   		return $results;
   	}
   	public function updateSources(){
   		$sources = $this->getAll();
   		foreach ($sources as $key => $source) {
   			$this->feeds_model->writeFeeds($source['url']);
   		}
   	}

}