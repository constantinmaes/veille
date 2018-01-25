		
		<div class="subtitle is-6">Filtres</div>
			<form>
				<select name="filter" id="filter">
					<option value="all">Tout</option>
					<option value="published">Publié</option>
					<option value="discarded">Oublié</option>
					<option value="waiting">En attente</option>
				</select>
			</form>
			<div>
				<p>Nombre de résultats dans le filtre : <span id="filter_results"></span></p>
			</div>
		<table class="table is-striped is-hoverable is-fullwidth">
			<thead>
					<td>ID</td>
					<td>Titre de la page</td>
					<td>URL</td>
					<td>Date de recuperation</td>
			</thead>
			<tbody id="content">
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

<script>
	$( document ).ready(function() {
		var filter_results= $('#content tr').length;
		$('#filter_results').html(filter_results);
		$("#filter").change(function() {
			var filter = $('#filter option:selected').val();
			$.ajax({
			url: '../admin/all_refresh',
			type: 'POST',
			data: {
				filter : filter,
			},
			success:function(result){
				if(result){
					$('#content').html(result);
					filter_results= $('#content tr').length;
					$('#filter_results').html(filter_results);
				}
				else{
					alert('pb');
				}
			}
			});
		});
	});

</script>
