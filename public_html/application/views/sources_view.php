
		<!--<table class="table is-striped is-hoverable is-fullwidth">
			<thead>
					<td>ID</td>
					<td>Titre de la page</td>
					<td>Site</td>
					<td>URL</td>
					<td>Date de recuperation</td>
			</thead>
			<tbody>
		<?php
			foreach($feeds as $feed){
				echo '<tr>';
				echo '<td>'.$feed['id_feed'].'</td>';
				echo '<td>'.$feed['title'].'</td>';
				echo '<td>'.$feed['website'].'</td>';
				echo '<td><a href="'.$feed['url'].'" target="_blank">Lien</a></td>';
				echo '<td>'.$feed['retrieval_date'].'</td>';
				echo '</tr>';
			}	
		?>
			</tbody>
		</table>-->

