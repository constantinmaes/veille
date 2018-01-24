<!DOCTYPE html>
<html>
 
    <head>
        <title>Veille technologique | Admin</title>
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.1/css/bulma.min.css" />
        <link rel="stylesheet" href="/assets/template.css" />
    	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
 
    <body class="is-light">
        <div class="navbar is-blue">
            <div class="navbar-brand">
                <a class="navbar-item" href="./admin">
                    <h3 class="subtitle is-5 text-white">Veille technologique</h3>
                </a>
            </div>
            <div class="navbar-end">
                <div class="navbar-item">
                    <a class="text-white">Se déconnecter</a>
                </div>
            </div>
        </div>
        <div class="level">
        </div>
        <div class="columns">
            <aside class="column is-2 is-fullheight menu">
                <p class="menu-label">
                    Modules
                </p>
                <ul class="menu-list">
                    <li><a href="./all">Toutes les news</a></li>
                    <li><a href="./waitlist">Carousel</a></li>
                    <li><a href="./keywords">Mots-clés</a></li>
                    <li><a>Vue statistique</a></li>
                </ul>
                <p class="menu-label">
                    Actions
                </p>
                <ul class="menu-list">
                    <li style="padding-left:8px;padding-right:8px;"><a class="button is-primary" href="../news">Rechercher les news</a></li>
                </ul>
            </aside>
    	    <div class="column container">
        	   <?php echo $body; ?> 
            </div>
        </div>
    </body>
     
</html>
