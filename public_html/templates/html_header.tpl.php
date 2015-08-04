<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no" />
<?php if ($data['config_slim_header'] === 'true') { ?>
	<style type="text/css">
		#header_logo { display: none; }		
		#header_navi { background: url(public_html/img/logo.png) no-repeat right; background-size: 15%; }
	</style>
<?php } ?>
	<link type="text/css" rel="stylesheet" href="public_html/css/style.css" />
	<link type="text/css" rel="stylesheet" href="public_html/css/mobile.css" media="screen and (max-device-width: 899px)" />
	<link type="text/css" rel="stylesheet" href="public_html/css/form.css" />
	<link rel="shortcut icon" href="public_html/img/favicon.png" type="image/png" />
	<script type="text/javascript" src="public_html/js/jquery.min.js"></script>
	<script type="text/javascript">var time = <?php echo $data['javascript_time']; ?>; var req_url = '{<?php echo $data['javascript_req_url']; ?>}';</script>
	<script type="text/javascript" src="public_html/js/javascript.js"></script>
	<script type="text/javascript" src="public_html/js/feedback.js"></script>
	<title><?php echo $data['title']; ?></title>
</head>
<body>
<!-- Header -->
	<div id="header">
		<div id="header_logo">
			<img src="public_html/img/logo.png" />
		</div>
		<div id="header_navi">
			<input type="checkbox" id="mobile_menu" /><label for="mobile_menu" class="mobile_menu"><span class="navi_img mobile_icon"></span></label>
			<div class="mobile_menu_content">
				<a href="?s=overview"><span class="navi_img overview_icon"></span> <?php _e('Übersicht'); ?></a>
				<a href="?s=network"><span class="navi_img network_icon"></span> <?php _e('Netzwerk'); ?></a>
				<a href="?s=statistic"><span class="navi_img statistic_icon"></span> <?php _e('Statistik'); ?></a>
				<a href="?s=terminal"><span class="navi_img terminal_icon"></span> <?php _e('Terminal'); ?></a>
				<div class="navi_dropdown"><a href="?s=plugins"><span class="navi_img plugins_icon"></span> <?php _e('Plugins'); ?></a>
					<div class="navi_dropdown_container">
						<div class="box margin-0">
						<?php if (is_array($data['navi_plugin_available'])) { ?>
							{loop="$navi_plugin_available"}<div class="navi_dropdown_item padding-0 inner"><a href="?s=plugins&id={$value.id}">{$value.name}</a></div>{/loop}
						<?php } else { ?>
							<div class="navi_dropdown_item padding-10 inner text-align-center"><?php echo $data['navi_plugin_available']; ?></div>
						<?php } ?>
						</div>
					</div>
				</div>
				<?php if (isset($navi_plugin_updates) && !empty($navi_plugin_updates)) { ?>
					<span class="plugin_update" title="Es steht ein Update für folgende(s) Plugin(s) zur Verfügung:&#10;{loop="$navi_plugin_updates"} - {$value.name} ({$value.version})&#10;{/loop}">UPDATE</span>
				<?php } ?>
			</div>
		</div>
	</div>
<!-- Content -->
	<div id="content">
<?php if (is_array($data['update_picontrol'])) { ?>
<!-- Update -->
		<div>
			<div class="box pulse_background">
				<div class="inner-header info_yellow">
					<span>Pi Control {$update_picontrol.version} ist verfügbar</span>
					<img src="public_html/img/update_animation.gif" style="float: right; height: 46px; margin: -10px; opacity: 0.5;" />
				</div>
				<div class="inner">
					<a href="?s=settings&amp;do=update" style="text-decoration: none;"><input type="button" value="Zur Update-Übersicht" /></a>
				</div>
			</div>
		</div>
<?php } ?>
<? if ($data['last_cron_execution'] < time()) { ?>
<!-- Cron -->
		<div>
			<div class="box info_red">
				<div class="inner">
					<strong><?php _e('Es gibt anscheinend ein Problem mit dem Cron für das Pi Control. Dieser wurde seit mehr als 2 Minuten nicht mehr ausgeführt. Sollte diese Meldung in ca. 5 Minuten immer noch erscheinen, führe eine <a href="?s=settings&amp;do=trouble-shooting">Problembehandlung</a> durch.'); ?></strong>
				</div>
			</div>
		</div>
<?php } ?>