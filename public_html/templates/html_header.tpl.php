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
						<?php _e('Angemeldet als %s', $data['username']); ?>
					</div>
					<div class="header-top-inner-cell header-top-inner-logout">
						<a href="?i=login&amp;logout<?php echo $data['referer']; ?>"><?php _e('Abmelden'); ?></a>
					</div>
				</div>
			</div>
		</div>
		<input type="checkbox" id="header-mobile" />
		<div id="inner-header">
			<label for="header-mobile"></label>
			<a href="?s=overview" id="header-logo"><img src="public_html/img/logo.svg" /></a>
			<div id="header-navi">
				<a href="?s=overview"><?php _e('&Uuml;bersicht'); ?></a>
				<a href="?s=network"><?php _e('Netzwerk'); ?></a>
				<a href="?s=statistic"><?php _e('Statistik'); ?></a>
				<a href="?s=terminal"><?php _e('Terminal'); ?></a>
				<div class="navi-dropdown"><a href="?s=plugins"><?php _e('Plugins'); if (is_array($data['naviPluginsUpdates'])) echo '<span class="update-bull">&bull;</span>'; ?></a>
					<div class="navi-dropdown-container">
<?php if (isset($data['naviPluginsUpdates'])) { ?>
						<a href="?s=discover_plugins" class="navi-dropdown-update">UPDATE</a>
<?php } if (is_array($data['naviPlugins'])) { foreach ($data['naviPlugins'] as $plugin) { ?>
						<a href="?s=plugins&amp;id=<?php echo $plugin['id']; ?>"><?php _e($plugin['name']); if (isset($data['naviPluginsUpdates'][$plugin['id']])) echo '<span class="update-bull">&bull;</span>'; ?></a>
<?php } } ?>
					</div>
				</div>
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
					<?php _e('Zur <a href="%s">Aktualisierung</a>, um diese anzusehen und zu starten.', '?s=settings&amp;do=update'); ?>
				</div>
			</div>
		</div>
<?php } ?>
<?php if ($data['cronExecutionFault'] === true) { ?>
<!-- Cron -->
		<div class="box error">
			<div>
				<div class="inner-header">
					<span><?php _e('Fehler mit dem Cron'); ?></span>
					<div><span class="cancel"></span></div>
				</div>
				<div class="inner">
					<?php _e('Es gibt anscheinend ein Problem mit dem Cron für das Pi Control. Dieser wurde seit mehr als 2 Minuten nicht mehr ausgef&uuml;hrt. Sollte diese Meldung in ca. 5 Minuten immer noch erscheinen, f&uuml;hre eine <a href="%s">Problembehandlung</a> durch.', '?s=settings&amp;do=trouble-shooting'); ?>
				</div>
			</div>
		</div>
<?php } ?>