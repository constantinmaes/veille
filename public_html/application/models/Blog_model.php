<?php
class Blog_model extends CI_model {
	public function __construct()
        {
                $this->load->database();
        }
	public function getAllPosts(){//Récuperer tous les posts
		$posts = array();
		$this->db->select('*');
		$this->db->from('feeds');
		$this->db->join('keywords', 'feeds.id_keyword = keywords.id_keyword');
		$this->db->where('feeds.published', 1);
		$this->db->order_by('feeds.retrieval_date', 'ASC');
		$results = $this->db->get();
		foreach($results->result() as $row){
			$posts[] = array(
				'id_feed' => $row->id_feed,
				'title' => $row->title,
				'content' => $row->content,
				'url' => $row->url,
				'retrieval_date' => $row->retrieval_date,
				'published' => $row->published,
				'id_keyword' => $row->id_keyword,
				'keyword' => $row->word,
				'rss_url' => $row->rss_url,
			);
		}
		return $posts;
	}
}
?>