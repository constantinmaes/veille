<?php
	$this->load->database();
	$this->load_model('news_model');
	$id_feed = $_POST['id_feed'];
	$data = array(
        'published' => 1,
	);
	$this->db->where('id_feed', $id);
	$this->db->update('feeds', $data);
	return 1;
	echo $result;
?>