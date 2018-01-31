<!--<pre><?php var_dump($news)?></pre>-->
<div class="columns">
	<div class="column is-6 columns">
		<div class="column is-2">
			<button class="button is-info is-pulled-left" id="prev">Précédent</button>
		</div>
		<div class="column is-8">
	<?php
		foreach ($news as $article) {
			if($article['published']==0 && $article['discarded']==0){
				echo 
					'<div class="box news" style="display:none;" id="'.$article['id_feed'].'">
						<h1>'.$article['title'].'</h1>
						<br>
						<p>
							<span style="color:grey;">ID : '.$article['id_feed'].' | '.$article['retrieval_date'].'</span> | <a id="url'.$article['id_feed'].'" href="'.$article['url'].'" target="_blank">Lien</a>
						</p>
						<p class="tags">
							'.$article['tags'].'
						</p>
						<br>
						<p>
							'.$article['content'].'
						</p>
						<br>
						<button class="button is-centered is-primary publish" value="'.$article['id_feed'].'">Publier</button>
						<button class="button is-centered is-danger discard" value="'.$article['id_feed'].'">Oublier</button>
						<input type="text" class="add_tag" placeholder="Ajouter un tag" />
					</div>';
			}
		}
	?>
		</div>
		<div class="column is-2">
			<button class="button is-info is-pulled-right" id="next">Suivant</button>
		</div>
	</div>
	<div class="column is-6" id="content">
		<iframe src="" width="100%" height="1000px"></iframe>
	</div>
</div>
<script>
	$( document ).ready(function() {
		
		var idArray = [];
		var index = 0;
		$('.news').each(function () {
    		idArray.push(this.id);
		});
		idArraySize = idArray.length;
		$('#prev').hide();
		$('#'+idArray[0]).fadeIn();

		$('#next').click(function(){
			$('#'+idArray[index]).fadeOut();
			index += 1;
			if(index<=0){
				$('#prev').hide();
			}
			else{
				$('#prev').show();
			}
			if(index>=idArray){
				$('#next').hide();
			}
			else{
				$('#next').show();
			}
			$('#'+idArray[index]).delay(500).fadeIn();
			var id_feed = idArray[index];
			var url = $('#url'+id_feed).attr("href");
			url = url.replace("https://www.google.com/url?rct=j&sa=t&url=","");
			index_ct = url.indexOf("&ct");
			url = url.substring(0,index_ct);
			//alert(url);
			$("iframe").attr("src",url);
			/*$.ajax({
					url: '../news/displayWebsite',
					type: 'POST',
					data: {
						id_feed : id_feed,
						url : url,
					},

					success:function(result){
						console.log(result);
						//alert(result);
						$("#content").html(result);
					}
				});*/
			
		});

		$('#prev').click(function(){
			$('#'+idArray[index]).fadeOut();
			index -= 1;
			if(index<=0){
				$('#prev').hide();
			}
			else{
				$('#prev').show();
			}
			if(index>=idArray){
				$('#next').hide();
			}
			else{
				$('#next').show();
			}
			$('#'+idArray[index]).delay(500).fadeIn();
		});

		$('.publish').click(function(){
			var id_feed = $(this).val();
			//alert($(this).val());
			$.ajax({
			url: '../news/publish',
			type: 'POST',
			data: {
				id_feed : id_feed,
			},

			success:function(result){
				if(result){
					$('#next').click();
				}
				else{
					//alert('pb');
				}
			}
			});
		});

		$('.discard').click(function(){
			var id_feed = $(this).val();
			$.ajax({
			url: '../news/discard',
			type: 'POST',
			data: {
				id_feed : id_feed,
			},

			success:function(result){
				if(result){
					$('#next').click();
				}
				else{
					//alert('pb');
				}
			}
			});
		});

		$(".add_tag").on('keyup', function (e) {
    		if (e.keyCode == 13) {
    			var id_feed = $(this).parent().attr("id");
    			var tag = $(this).val();
    			$.ajax({
					url: '../news/addTag',
					type: 'POST',
					data: {
						id_feed : id_feed,
						tag : tag,
					},

					success:function(result){
						if(result){
							//alert("Tag added");
						}
						else{
							//alert('pb');
						}
					}
				});
        		$(this).val("");
        		$.ajax({
					url: '../news/refreshTags',
					type: 'POST',
					data: {
						id_feed : id_feed,
					},

					success:function(result){
						if(result){
							//$(this).parent.('.tags').html(result);
						}
						else{
							//alert('pb');
						}
					}
				});

    		}
		});

	});
</script>