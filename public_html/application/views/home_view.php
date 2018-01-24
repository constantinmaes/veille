<?php
foreach($posts as $post){
	echo '<div class="column is-6">
		<h1 class="subtitle is-4">'.$post['title'].'</h1>
		<p>'.$post['content'].'</p>
		<br><hr><br>
	</div>';
}
?>
