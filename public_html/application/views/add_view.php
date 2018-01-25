<div class="subtitle is-4">Ajouter un article</div>
<form method="post" action="">
	<p>
		<input type="text" name="title" placeholder="Titre" />
	</p>
	<br>
	<p>
		<input type="url" name="url" placeholder="URL" />
	</p>
	<br>
	<p>
		<input type="text" name="content" placeholder="Contenu">
	</p>
	<br>
	<p>
		<input type="text" name="retrieval_date" placeholder="Date" />
	</p>
	<br>
	<p>
		<select name="id_keyword">
			<option></option>
			<?php
				foreach($keywords as $keyword){
					echo '<option value='.$keyword['id_keyword'].'>'.$keyword['word'].'</option>';
				}
			?>
		</select>
	</p>
	<br>
	<p>
		<input class="button is-primary" type="submit" name="bAdd" value="Ajouter" />
	</p>
</form>