
		<table class="table is-striped is-hoverable is-fullwidth">
			<thead>
					<td>ID</td>
					<td>Titre de la page</td>
					<td>URL</td>
					<td>Date de recuperation</td>
			</thead>
			<tbody>
		<?php
			foreach($news as $article){
				echo '<tr>';
				echo '<td>'.$article['id_feed'].'</td>';
				echo '<td>'.$article['title'].'</td>';
				echo '<td><a href="'.$article['url'].'" target="_blank">Lien</a></td>';
				echo '<td>'.$article['retrieval_date'].'</td>';
				echo '</tr>';
			}	
		?>
			</tbody>
		</table>

