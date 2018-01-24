<?php
	echo "alert('ok php')";
	$id_feed = $_POST['id_feed'];
	$result = $this->news_model->publishNews($id_feed);
	echo $result;
?>