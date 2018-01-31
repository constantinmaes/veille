		
		<div class="subtitle is-6">Outils</div>
			<form class="columns">
				<div class="column is-3">
					Filtrer : 
					<select name="filter" id="filter">
						<option value="all">Tout</option>
						<option value="published">Publié</option>
						<option value="discarded">Oublié</option>
						<option value="waiting">En attente</option>
					</select>
				</div>
				<!--<div class="column is-3">
					Rechercher : <input type="text" id="search_term" name="search_term" value=" " />
				</div>
				<div class="column is-3">
					Trier par  : 
					<select name="order" id="order">
						<option value="retrieval_date">Date</option>
						<option value="id_feed">ID</option>
						<option value="url">Site</option>
					</select>
				</div>-->
			</form>
			<br>
			<div>
				<p>Nombre de résultats dans le filtre : <span id="filter_results"></span></p>
			</div>
			<br>
		<table class="table is-striped is-hoverable is-fullwidth">
			<thead>
					<td>ID</td>
					<td>Titre de la page</td>
					<td>URL</td>
					<td>Site</td>
					<td>Date de recuperation</td>
			</thead>
			<tbody id="content">
		<?php
			foreach($news as $article){
				echo '<tr>';
				echo '<td>'.$article['id_feed'].'</td>';
				echo '<td>'.$article['title'].'</td>';
				echo '<td><a href="'.$article['url'].'" target="_blank">Lien</a></td>';
				echo '<td>'.$article['website'].'</td>';
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
			var order = $('#order option:selected').val();
			var search_term = $('#search_term').val();
			$.ajax({
			url: '../admin/all_refresh',
			type: 'POST',
			data: {
				filter : filter,
				search_term : search_term,
				order : order,
			},
			success:function(result){
				if(result){
					$('#content').html(result);
					filter_results= $('#content tr').length;
					$('#filter_results').html(filter_results);
				}
				else{
					$('#content').html("Il n'y a pas de résultats.");
					$('#filter_results').html("0");
				}
			}
			});
		});
		$("#order").change(function() {
			var filter = $('#filter option:selected').val();
			var order = $('#order option:selected').val();
			var search_term = $('#search_term').val();
			$.ajax({
			url: '../admin/all_refresh',
			type: 'POST',
			data: {
				filter : filter,
				search_term : search_term,
				order : order,
			},
			success:function(result){
				if(result){
					$('#content').html(result);
					filter_results= $('#content tr').length;
					$('#filter_results').html(filter_results);
				}
				else{
					$('#content').html("Il n'y a pas de résultats.");
					$('#filter_results').html("0");
				}
			}
			});
		});
		$("#search_term").keyup(function() {
			var filter = $('#filter option:selected').val();
			var order = $('#order option:selected').val();
			var search_term = $('#search_term').val();
			$.ajax({
			url: '../admin/all_refresh',
			type: 'POST',
			data: {
				filter : filter,
				search_term : search_term,
				order : order,
			},
			success:function(result){
				if(result){
					$('#content').html(result);
					filter_results= $('#content tr').length;
					$('#filter_results').html(filter_results);
				}
				else{
					$('#content').html("Il n'y a pas de résultats.");
					$('#filter_results').html("0");
				}
			}
			});
		});
	});

</script>
