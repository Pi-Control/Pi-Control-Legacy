<?php if (!defined('PICONTROL')) exit(); ?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no" />
	<meta name="theme-color" content="#1565C0" />
	<link type="text/css" rel="stylesheet" href="public_html/css/style.css.php" />
	<link rel="shortcut icon" href="public_html/img/favicon.png" type="image/png" />
	<script type="text/javascript" src="public_html/js/jquery.min.js"></script>
	<script type="text/javascript">var req_url = '{<?php echo $data['javascript_req_url']; ?>}';</script>
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
			<div id="header-navi">
				<a href="?s=overview"><?php _e('&Uuml;bersicht'); ?></a>
				<a href="?s=network"><?php _e('Netzwerk'); ?></a>
				<a href="?s=statistic"><?php _e('Statistik'); ?></a>
				<a href="?s=terminal"><?php _e('Terminal'); ?></a>
				<div class="navi-dropdown"><a href="?s=plugins"><?php _e('Plugins'); ?></a>
					<div class="navi-dropdown-container">
<?php if (is_array($data['navi_plugins'])) {
	foreach ($data['navi_plugins'] as $plugin) { ?>
						<a href="?s=plugins&amp;id=<?php echo $plugin['id']; ?>"><?php _e($plugin['name']); ?></a>
<?php } } /*else { echo '<span style="margin: 20px; display: block; text-align: center;">'.$data['navi_plugins'].'</span>'; }*/ ?>
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
					<span>Nicht unterst&uuml;tzte Browserversion</span>
				</div>
				<div class="inner">
					Deine aktuelle Browserversion wird von Pi Control nicht unterst&uuml;tzt. Bitte aktualisiere deinen Browser oder verwende einen anderen!
				</div>
			</div>
		</div>
		<![endif]-->
<?php if (is_array($data['updatePicontrol'])) { ?>
<!-- Update -->
		<div class="box info info-update pulse-update">
			<div>
				<div class="inner-header">
					<span>Pi Control <?php echo $data['updatePicontrol']['version']; ?> ist verf&uuml;gbar</span>
				</div>
				<div class="inner">
					Zur <a href="?s=settings&amp;do=update">Aktualisierung</a>, um diese anzusehen und zu starten.
				</div>
			</div>
		</div>
<?php } ?>
<?php if ($data['cron_execution_fault'] === true) { ?>
<!-- Cron -->
		<div class="box error">
			<div>
				<div class="inner-header">
					<span>Fehler mit dem Cron</span>
					<div><span class="cancel"></span></div>
				</div>
				<div class="inner">
					<?php _e('Es gibt anscheinend ein Problem mit dem Cron für das Pi Control. Dieser wurde seit mehr als 2 Minuten nicht mehr ausgeführt. Sollte diese Meldung in ca. 5 Minuten immer noch erscheinen, führe eine <a href="?s=settings&amp;do=trouble-shooting">Problembehandlung</a> durch.'); ?>
				</div>
			</div>
		</div>
<?php } ?>