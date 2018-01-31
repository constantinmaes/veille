<?php
class News_model extends CI_model {
	public function __construct()
        {
                $this->load->database();
        }
	public function getAllNews(){//Récuperer toutes les news de la db
		$tags = array();
		$this->db->select('*');
		$this->db->from('tags_feeds');
		$this->db->join('tags','tags_feeds.id_tag=tags.id_tag','full');
		$results = $this->db->get();
		foreach($results->result() as $row){
			$tags[] = array(
				'id_feed' => $row->id_feed,
				'tag' => $row->tag,
			);

		}
		$news = array();
		$this->db->select('*');
		$this->db->from('feeds');
		$this->db->join('keywords', 'feeds.id_keyword = keywords.id_keyword');
		if(isset($_POST['filter'])){
			if($_POST['filter']=='published'){
				$this->db->where('published',1);
			}
			elseif($_POST['filter']=='discarded'){
				$this->db->where('discarded',1);
			}
			elseif($_POST['filter']=='waiting'){
				$this->db->where('published',0);
				$this->db->where('discarded',0);
			}
			else{
			}
		}
		$this->db->order_by('feeds.retrieval_date', 'ASC');
		$results = $this->db->get();
		foreach($results->result() as $row){
			$website = str_replace('https://www.google.com/url?rct=j&sa=t&url=', '', $row->url);
			$website = str_replace('http://', '', $website);
			$website = str_replace('https://', '', $website);
			$website = str_replace('www.', '', $website);
			$website = substr($website, 0, strpos($website, '/'));
			$id_feed = $row->id_feed;
			$tags_string = '';
			foreach($tags as $index=>$tag){
				
					if($tag['id_feed']==$id_feed){
						$tags_string .= '#'.$tag['tag'].' ';
					}
				
			}
			$news[] = array(
				'id_feed' => $row->id_feed,
				'title' => $row->title,
				'content' => $row->content,
				'url' => $row->url,
				'website' => $website,
				'retrieval_date' => $row->retrieval_date,
				'published' => $row->published,
				'discarded' => $row->discarded,
				'id_keyword' => $row->id_keyword,
				'keyword' => $row->word,
				'rss_url' => $row->rss_url,
				'tags' => $tags_string,
			);
		}
		return $news;
	}
	public function getTagsForFeed($id_feed)
	{
		$tags = array();
		$this->db->select('*');
		$this->db->from('tags_feeds');
		$this->db->join('tags','tags_feeds.id_tag=tags.id_tag','full');
		$results = $this->db->get();
		foreach($results->result() as $row){
			$tags[] = array(
				'id_feed' => $row->id_feed,
				'tag' => $row->tag,
			);

		}
		$tags_string = '';
			foreach($tags as $index=>$tag){
				if($tag['id_feed']==$id_feed){
					$tags_string .= '#'.$tag['tag'].' ';
				}
			
		}
		echo $tags_string;
	}

	public function publishNews($id){
		$data = array(
        	'published' => 1,
		);
		$this->db->where('id_feed', $id);
		$this->db->update('feeds', $data);
		echo true;
	}

	public function discardNews($id){
		$data = array(
        	'discarded' => 1,
		);
		$this->db->where('id_feed', $id);
		$this->db->update('feeds', $data);
		echo true;
	}

	public function addNews()
	{
		$data = array(
			'title' => strip_tags($_POST['title']),
			'content' => strip_tags($_POST['content']),
			'url' => strip_tags($_POST['url']),
			'retrieval_date' => strip_tags($_POST['retrieval_date']),
			'published' => 0,
			'discarded' => 0,
			'id_keyword' => strip_tags($_POST['id_keyword']),
		);
		$this->db->insert('feeds', $data);
		$this->db->select('*');
		$this->db->from('feeds');
		$this->db->where('url',$_POST['url']);
		$add_result = $this->db->get();
		if($add_result->num_rows()!=0){
			return true;
		}
		else{
			return false;
		}
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
				if($result1['title']==$result2['title'] && $result1['id_feed']!=$result2['id_feed'] && !in_array($result1['id_feed'],$feeds_to_delete) && !in_array($result2['id_feed'], $feeds_to_delete)){
						$feeds_to_delete[]=$result2['id_feed'];
						$this->db->where('id_feed', $result2['id_feed']);
						$this->db->delete('feeds');
				}
			}
		}
		
	}
	public function parseWebsite($id,$url)
	{
		
		$url = str_replace('https://www.google.com/url?rct=j&sa=t&url=', '', $url);
		$url = substr($url, 0, strpos($url, '&ct'));
		$curl_handle=curl_init();
	    curl_setopt($curl_handle, CURLOPT_URL, $url);
	    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
	    $content = curl_exec($curl_handle);
	    curl_close($curl_handle);
	    $content = str_replace(substr($content, 0, strpos($content, '<body>')), '', $content);
	    $content = preg_replace('@<style[^>]*?>.*?</style>@siu', '', $content);
	    $content = preg_replace('@<head[^>]*?>.*?</head>@siu', '', $content);
	    $content = preg_replace('@<script[^>]*?>.*?</script>@siu', '', $content);
	    $content = preg_replace('@<aside[^>]*?>.*?</aside>@siu', '', $content);
	    $content = preg_replace('@<nav[^>]*?>.*?</nav>@siu', '', $content);
	    $content = preg_replace('@<header[^>]*?>.*?</header>@siu', '', $content);
	    $content = preg_replace('@<footer[^>]*?>.*?</footer>@siu', '', $content);
	    $content = strip_tags($content);
		return $content;
	}

	public function displayWebsiteContent($id,$url)
	{
		$url = str_replace('https://www.google.com/url?rct=j&sa=t&url=', '', $url);
		$url = substr($url, 0, strpos($url, '&ct'));
		$curl_handle=curl_init();
	    curl_setopt($curl_handle, CURLOPT_URL, $url);
	    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
	    $content = curl_exec($curl_handle);
	    curl_close($curl_handle);
	    /*$content = str_replace(substr($content, 0, strpos($content, '<body>')), '', $content);
	    $content = preg_replace('@<style[^>]*?>.*?</style>@siu', '', $content);
	    $content = preg_replace('@<head[^>]*?>.*?</head>@siu', '', $content);*/
	    $content = preg_replace('@<script[^>]*?>.*?</script>@siu', '', $content);
	    $content = preg_replace('@<aside[^>]*?>.*?</aside>@siu', '', $content);
	    $content = preg_replace('@<nav[^>]*?>.*?</nav>@siu', '', $content);
	    $content = preg_replace('@<header[^>]*?>.*?</header>@siu', '', $content);
	    $content = preg_replace('@<footer[^>]*?>.*?</footer>@siu', '', $content);
	    $content = strip_tags($content);
		return $content;
		//return $url;
	}
	public function writeWebsiteContentToFile($content){
		$file = fopen('assets/website_content','w+');
		fwrite($file,$content);
		fclose($file);
	}
	public function countOccurrences(){
		$output = array();
		$analysis = array();
		$results = array();
		//for($i=0;$i<10;$i++){
			$command = 'python assets/split.py';	
			exec($command,$output);
			foreach($output as $key=>$val){
				if($key%2==0){
					$analysis[][$val] = $output[$key+1];
				}
			}
		//}
		foreach($analysis as $id){
			foreach($id as $key=>$val){
				if(!isset($results[$key])){
					$results[$key] = 0;
				}
				$results[$key] += $val;
			}
		}
		return $results;
	}
	public function analyseAll()
	{
		$news = $this->getAllNews();
		foreach($news as $article){
			if($article['id_feed']>200 && $article['id_feed']<301){
			$content = $this->parseWebsite($article['id_feed'],$article['url']);
			$this->writeWebsiteContentToFile($content);
			$results[$article['id_feed']] = $this->countOccurrences();
			}
		}
		return $results;
	}
	public function addTag($id,$tag)
	{
		$this->db->select('*');
		$this->db->from('tags');
		$this->db->where('tag',$tag);
		$tag_target = $this->db->get();
		if($tag_target->num_rows()==0){
			$data = array(
				'tag' => $tag,
			);
			$this->db->insert('tags', $data);
		}
		$this->db->select('id_tag');
		$this->db->from('tags');
		$this->db->where('tag',$tag);
		$results = $this->db->get();
		foreach($results->result() as $row){
			$id_tag = $row->id_tag;
		}
		$data = array(
				'id_tag' => $id_tag,
				'id_feed' => $id,
			);
		$this->db->insert('tags_feeds',$data);
		echo true;
	}
	public function getTags($id)
	{
		$tags = array();
		$this->db->select('*');
		$this->db->from('tags_feeds');
		$this->db->join('tags', 'tags_feeds.id_tag = tags.id_tag');
		$this->db->where('id_feed',$id);
		$results = $this->db->get();
		if($results->num_rows()==0){
			foreach($results->result() as $row){
				$tags[] = '#'.$row->tags.id_tag;
			}
		}
		$tags = implode(',', $tags);
		echo $tags;
	}
	public function analyseTags()
	{
		$tags = array();
		$this->db->select('*');
		$this->db->from('tags');
		$this->db->join('tags_feeds','tags.id_tag=tags_feeds.id_tag');
		$this->db->join('feeds','tags_feeds.id_feed=feeds.id_feed');
		$results = $this->db->get();
		if($results->num_rows()==0){
			foreach($results->result() as $row){
				$tags[] = array(
					'id_tag' => $row->tags.id_tag,
					'tag' => $row->tags.tag,
					'id_feed' => $row->feeds.id_feed,
					'retrieval_date' => $row->feeds.retrieval_date,
				);
			}
		}
		return $results;
	}
}
?>
