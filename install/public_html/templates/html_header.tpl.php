<?php if (!defined('PICONTROL')) exit(); ?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no" />
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
	<title><?php echo $data['title']; ?></title>
</head>
<body>
<!-- Header -->
	<div id="header">
		<div id="header-top">
			<div id="header-top-inner">
				<div id="header-top-inner-row">
					<div class="header-top-inner-cell header-top-inner-username">
						&nbsp;
					</div>
				</div>
			</div>
		</div>
		<input type="checkbox" id="header-mobile" />
		<div id="inner-header">
			<label for="header-mobile"></label>
			<a href="?s=install<?php echo $data['langUrl']; ?>" id="header-logo"><img src="public_html/img/logo.svg" /></a>
			<div id="header-navi">
				<a href="?s=install<?php echo $data['langUrl']; ?>"><?php _e('Installation'); ?></a>
			</div>
		</div>
	</div>
<!-- Content -->
	<div id="content">
		<!--[if lte IE 9]>
		<div class="box info">
			<div>
				<div class="inner-header">
					<span><?php _e('Nicht unterst&uuml;tzte Browserversion'); ?></span>
				</div>
				<div class="inner">
					<?php _e('Deine aktuelle Browserversion wird von Pi Control nicht unterst&uuml;tzt. Bitte aktualisiere deinen Browser oder verwende einen anderen!'); ?>
				</div>
			</div>
		</div>
		<![endif]-->
<?php if (is_array($data['updatePicontrol'])) { ?>
<!-- Update -->
		<div class="box info info-update pulse-update">
			<div>
				<div class="inner-header">
					<span><?php _e('Pi Control %s ist verf&uuml;gbar', $data['updatePicontrol']['version']); ?></span>
				</div>
				<div class="inner">
					<?php _e('Es steht ein Update zur Verf&uuml;gung. Bitte aktualisiere auf die neuste Version, bevor du die Installation startest: <a href="%s" target="_blank">Download</a>', 'https://pi-control.de/beta/'); ?>
				</div>
			</div>
		</div>
<?php } ?>