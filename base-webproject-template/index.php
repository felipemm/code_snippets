<?php
	include 'include/config.php';
	include 'include/session.php';

	$session = new Session(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
	
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<meta property="og:title" content="" />
		<meta property="og:type" content="activity" />
		<meta property="og:url" content="" />
		<meta property="og:image" content="" />
		<meta property="og:site_name" content="" />
		<meta property="fb:admins" content="" />
		<meta property="og:description" content=""/>
        <title>FelipeMatos.com - Mapfisher</title>
        <link rel="stylesheet" type="text/css" href="http://cdn.sencha.io/ext-4.1.0-gpl/resources/css/ext-all.css" />
        <script type="text/javascript" charset="utf-8" src="http://cdn.sencha.io/ext-4.1.0-gpl/ext-all.js"></script>
        
        <link rel="stylesheet" type="text/css" href="css/main.css?<?php echo filemtime('css/main.css'); ?>" />

		<script type="text/javascript" charset="utf-8" src="js/common.js?<?php echo filemtime('js/common.js'); ?>"></script>
		<script type="text/javascript" charset="utf-8" src="js/login.js?<?php echo filemtime('js/login.js'); ?>"></script>
		<script type='text/javascript' charset="utf-8" src='js/main.js?<?php echo filemtime('js/main.js'); ?>'></script>
		
		<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
		  {"parsetags": "explicit"}
		</script>
		<script type="text/javascript">
		  function renderPlusone() {
			gapi.plusone.render("gplus");
		  }
		</script>
		
    </head>
    <body>
		<div id="fb-root"></div>
		<script>
			(
				function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=398547063544255";
					fjs.parentNode.insertBefore(js, fjs);
				}
				(document, 'script', 'facebook-jssdk')
			);
		</script>	
		<script type="text/javascript">
			if (location.hash) {
				String.locale = location.hash.substr(1);	
			}

			var localize = function (string, fallback) {
				var localized = string.toLocaleString();
				
				if (localized !== string) {
					return localized;
				} else {
					var l = String.locale;
					String.locale = "en";
					localized = string.toLocaleString();
					String.locale = l;
					if (localized !== string) {
						return localized;
					} else {
						return string;
					}
				}
			};	
		</script>
    <body>
    </body>
</html>