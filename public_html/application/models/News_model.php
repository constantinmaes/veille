<?php
class News_model extends CI_model {
	public function __construct()
        {
                $this->load->database();
        }
	public function getAllNews(){//Récuperer toutes les news de la db
		$news = array();
		$this->db->select('*');
		$this->db->from('feeds');
		$this->db->join('keywords', 'feeds.id_keyword = keywords.id_keyword');
		$this->db->order_by('feeds.retrieval_date', 'ASC');
		$results = $this->db->get();
		foreach($results->result() as $row){
			$news[] = array(
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
		return $news;
	}

	public function publishNews($id){
		$data = array(
        	'published' => 1,
		);
		$this->db->where('id_feed', $id);
		$this->db->update('feeds', $data);
		echo true;
	}

	public function getAllKeywords(){
		$keywords = array();
		$this->db->select('*');
		$this->db->from('keywords');
		$results = $this->db->get();
		foreach($results->result() as $row){
			$keywords[] = array(
				'id_keyword' => $row->id_keyword,
				'word' => $row->word,
				'rss_url' => $row->rss_url,
			);
		}
		return $keywords;
	}

	public function createKeyword($word, $rss_url){
		$this->db->select('*');
		$this->db->from('keywords');
		$this->db->where('rss_url',$rss_url);
		$keyword_target = $this->db->get();
		if($keyword_target->num_rows()==0){
			$data = array(
				'word' => $word,
				'rss_url' => $rss_url,
			);
			$this->db->insert('keywords', $data);
		}
	}

	public function getXML($feed_url) {//Récupérer le XML Google Alert
		$curl_handle=curl_init();
	    curl_setopt($curl_handle, CURLOPT_URL, $feed_url);
	    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
	    $query = curl_exec($curl_handle);
	    curl_close($curl_handle);
	    return $query;
	}
	public function getContentFeed($feed_url,$id_keyword){//Préparer un array des news issus du XML à sauvegarder
		$content = $this->getXML($feed_url);
		$x = new SimpleXMLElement($content);
		$news_to_save = array();
		foreach($x->entry as $entry){
			$url = strval($entry->{'link'}['href']);
			$news_to_save[] = array(
				'title' => strval($entry->{'title'}[0]),
				'content' => strval($entry->{'content'}),
				'url' => strval($entry->{'link'}['href']),
				'retrieval_date' => substr(str_replace('T', ' ',strval($entry->published)),0,-1),
				'published' => 0,
				'id_keyword' => $id_keyword,
			);
		}
		return $news_to_save;
	}
	public function compareDuplicates($news_source){//Comparer les feeds issus du XML à sauvegarder avec ceux qui existent dans la db et suppression des doublons dans les feeds à sauvegarder
		foreach ($news_source as $key=>$new_source) {
			$this->db->select('*');
			$this->db->from('feeds');
			$this->db->where('url',$new_source['url']);
			$news_target = $this->db->get();
			if($news_target->num_rows()!=0){
				unset($news_source[$key]);
			}
		}
		$news_to_save = $news_source;		
		return $news_to_save;
	}

	public function getFeedUrl($keyword){
		$this->db->select('rss_url');
		$this->db->from('keywords');
		$this->db->where('word',$keyword);
		$results = $this->db->get();
		foreach($results->result() as $row){
			$feed_url = $row->rss_url;
		}
		return $feed_url;
	}

	public function writeNews(){ //Sauvegarder les feeds récupérés et vérifiés contre la db.
		$keywords = $this->getAllKeywords();
		foreach($keywords as $keyword){
			$news_to_save = $this->getContentFeed($keyword['rss_url'],$keyword['id_keyword']);
			$news_to_save = $this->compareDuplicates($news_to_save);
			if(!empty($news_to_save)){
				$this->db->insert_batch('feeds',$news_to_save);
			}
		}
	}
	public function cleanDb(){ //Supprimer les doublons dans la base de données sur base de l'url (normalement pas nécessaire car check avant insertion).
		$results = array();
		$results=$this->getAllNews();
		/*$feeds = $this->db->get('feeds');
		foreach($feeds->result() as $row){
			$results[] = array(
				'id_feed' => $row->id_feed,
				'title' => $row->title,
				'website' => $row->website,
				'url' => $row->url,
				'retrieval_date' => $row->retrieval_date,
			);
		}*/
		$feeds_to_delete = array();
		foreach($results as $key1=>$result1){
			foreach($results as $key2=>$result2){
				if($result1['url']==$result2['url'] && $result1['id_feed']!=$result2['id_feed'] && !in_array($result1['id_feed'],$feeds_to_delete) && !in_array($result2['id_feed'], $feeds_to_delete)){
						$feeds_to_delete[]=$result2['id_feed'];
						$this->db->where('id_feed', $result2['id_feed']);
						$this->db->delete('feeds');
				}
			}
		}
		
	}
}
?>
