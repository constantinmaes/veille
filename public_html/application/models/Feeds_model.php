<?php
class Feeds_model extends CI_model {
	public function show(){
		$this->load->database();
		$results = array();
		$feeds = $this->db->get('feeds');
		foreach($feeds->result() as $row){
			$results[] = array(
				'id_feed' => $row->id_feed,
				'title' => $row->title,
				'website' => $row->website,
				'url' => $row->url,
				'retrieval_date' => $row->retrieval_date,
			);
		}
		return $results;
	}
	public function getContentFeed($feed_url){
		$content = file_get_contents($feed_url);
		$x = new SimpleXMLElement($content);
		$feeds_to_save = array();
		foreach($x->channel->item as $entry){
			$feeds_to_save[] = array(
				'title' => $entry->title,
				'url' => $entry->url,
				'retrieval_date' => $entry->pubDate,
			);
		}
		return $feeds_to_save;
	}
}
?>
