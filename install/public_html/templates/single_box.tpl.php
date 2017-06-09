<?php if (!defined('PICONTROL')) exit(); ?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-title" content="Pi Control" />
	<meta name="application-name" content="Pi Control" />
	<meta name="theme-color" content="#1565C0" />
	<link type="text/css" rel="stylesheet" href="public_html/css/style.css.php" />
	<link rel="apple-touch-icon" sizes="57x57" href="/public_html/img/favicon/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="/public_html/img/favicon/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="/public_html/img/favicon/apple-touch-icon-152x152.png" />
	<link rel="apple-touch-icon" sizes="180x180" href="/public_html/img/favicon/apple-touch-icon-180x180.png" />
	<link rel="icon" type="image/png" href="public_html/img/favicon/android-chrome-192x192.png" sizes="192x192" />
	<link rel="icon" type="image/png" href="public_html/img/favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="manifest" href="public_html/img/favicon/manifest.json" />
	<link rel="mask-icon" href="public_html/img/favicon/safari-pinned-tab.svg" color="#1975d0" />
	<link rel="shortcut icon" href="public_html/img/favicon/favicon.ico" />
	<script type="text/javascript" src="public_html/js/jquery.min.js"></script>
	<script type="text/javascript" src="public_html/js/main.js"></script>
	<script type="text/javascript" src="public_html/js/feedback.js"></script>
	<title>Pi Control</title>
</head>
<body>
<!-- Header -->
	<div id="header">
		<div id="inner-header" style="min-height: 45px;">
		</div>
	</div>
<!-- Content -->
	<div id="content" style="min-height: auto;">
		<div>
			<div class="box" style="margin: 0;">
				<?php echo $data['content']; ?>
			</div>
		</div>
	</div>
<!-- Footer -->
	<div id="footer" style="background: none; border: none; min-height: auto;">
		<div id="footer-inner">
			<div id="footer-copyright"><?php _e('Mit %s entwickelt von %s.', '<span style="color: #F44336;">&#10084;</span>', '<a href="https://willy-tech.de/" target="_blank">Willy Fritzsche</a>'); ?> 2013-2017</div>
		</div>
	</div>
</body>
</html>