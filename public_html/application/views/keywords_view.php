<h1>Gérer les mots-clés</h1>

<h2>Mots-clés existants</h2>
<div class="columns">
	<div class="column is-8">
		<table class="table is-striped is-hoverable is-fullwidth">
			<thead>
				<td>ID</td>
				<td>Mot-clé</td>
				<td>URL</td>
			</thead>
			<tbody>
			<?php
				foreach($keywords as $keyword){
					echo '<tr>';
					echo '<td>'.$keyword['id_keyword'].'</td>';
					echo '<td>'.$keyword['word'].'</td>';
					echo '<td><a href="'.$keyword['rss_url'].'" target="_blank">Lien vers le fil rss</a></td>';
					echo '</tr>';
				}	
			?>
			</tbody>
		</table>
	</div>
</div>
<h2>Ajouter un mot clé</h2>
<form class="" action="" method="post">
	<div class="field is-horizontal">
		<div class="field-body">
			<div class="field">
				<p class="control has-icons-left">
					<input class="input" type="text" placeholder="Mot-clé" name="word">
	        		<span class="icon is-small is-left">
	          			<i class="fa fa-key"></i>
	        		</span>
	      		</p>
	    	</div>
	    	<div class="field">
	      		<p class="control is-expanded has-icons-left has-icons-right">
	        		<input class="input" type="" placeholder="Lien Google Alert" value="" name="rss_url">
	        		<span class="icon is-small is-left">
	          			<i class="fa fa-url"></i>
	        		</span>
	      		</p>
	    	</div>
      		<div class="control">
        		<input class="button is-primary" type="submit" name="bSaveKeyword" id="bSaveKeyword" value="Enregistrer" />
      		</div>
    	</div>
  	</div>
</form>