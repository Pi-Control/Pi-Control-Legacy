<?php if (!defined('PICONTROL')) exit(); ?>
<script type="text/javascript"><?php echo $data['js_variables']; ?></script>
<script type="text/javascript" src="public_html/js/overview.status_refresh.js"></script>
<!-- Sidebar -->
<div class="sidebar order-2">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('System'); ?></span>
		</div>
		<div class="inner">
			<table class="table-overview-system">
				<tr>
					<td><a href="?s=shutdown&amp;restart" onClick="return ((confirm('<?php _e('M&ouml;chtest du deinen Raspberry Pi wirklich neu starten?'); ?>') == false) ? false : true);"><button><?php _e('Neu starten'); ?></button></a></td>
					<td></td>
					<td><a href="?s=shutdown" onClick="return ((confirm('<?php _e('M&ouml;chtest du deinen Raspberry Pi wirklich herunterfahren?'); ?>') == false) ? false : true);"><button class="system_shutdown"><?php _e('Herunterfahren'); ?></button></a></td>
				</tr>
			</table>
		</div>
	</div>
<?php if ($data['show_weather'] === true) { ?>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Wetter'); ?></span>
			<?php if ($data['weather_cache_hint'] != NULL) echo $data['weather_cache_hint']; ?>
		</div>
		<div class="inner">
	<?php if ($data['weather'] === 0) { ?>
			<strong class="red"><?php _e('Die aktuelle Postleitzahl ist ung&uuml;ltig.'); ?></strong>
	<?php } elseif ($data['weather'] === 2) { ?>
			<strong class="red"><?php _e('Wetter wurde noch nicht konfiguriert. <a href="%s">Zu den Einstellungen.</a>', '?s=settings&do=overview'); ?></strong>
	<?php } elseif ($data['weather'] === 1 || !is_array($data['weather'])) { ?>
			<strong class="red"><?php _e('Das Wetter kann aktuell nicht abgerufen werden.'); ?></strong>
	<?php } else { ?>
			<strong><?php echo $data['weather']['city']; ?></strong><br /><br />
			<table style="margin: 0px auto 0px;">
				<tr>
					<td style="width: 100px; text-align: center;">
						<img src="public_html/img/weather/<?php echo $data['weather']['icon']; ?>.svg" title="<?php echo $data['weather']['description']; ?>" alt="<?php _e('Wetter'); ?>" style="width: 64px;" /><br />
						<span style="font-size: 11px;" title="<? _e('Windst&auml;rke | Luftfeuchtigkeit'); ?>"><?php echo $data['weather']['wind']; ?> km/h | <?php echo $data['weather']['humidity']; ?> %</span>
					</td>
					<td style="width: 100px; text-align: center;"><span style="font-size: 30px;">
						<?php echo $data['weather']['temp']; ?> &deg;C</span><br />
						<span style="font-size: 13px;"><?php echo $data['weather']['temp_min']; ?> &deg;C | <?php echo $data['weather']['temp_max']; ?> &deg;C</span>
					</td>
				</tr>
			</table>
	<?php if ($data['weather']['service'] == 'yahoo') { ?><a href="https://www.yahoo.com/?ilc=401" target="_blank" style="float: right; margin-right: -12px;"><img src="public_html/img/weather/yahoo.png" width="80px" /></a><?php } ?>
	<?php } ?>
		</div>
	</div>
<?php } ?>
</div>
<!-- Container -->
<div class="container-600 order-1">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('&Uuml;bersicht'); ?></span>
			<?php showSettingsIcon('?s=settings&amp;do=overview'); ?>
		</div>
		<div class="inner flex-container">
			<div class="flex-box-refresh"><div><div class="refresh-bar"></div></div><a href="#refresh"><img src="public_html/img/refresh-icon.svg" title="<?php _e('Aktualisieren'); ?>" /></a></div>
			<div class="flex-box"><strong><?php _e('Startzeit'); ?></strong><span><?php echo $data['start_time']; ?></span></div>
			<div class="flex-box"><strong><?php _e('Laufzeit'); ?></strong><span><?php echo $data['run_time']; ?></span></div>
			<div class="flex-box"><strong><?php _e('CPU-Takt'); ?></strong><span><?php echo $data['cpu_clock']; ?></span></div>
			<div class="flex-box"><strong><?php _e('CPU-Auslastung'); ?></strong><div class="progressbar" data-text="<?php echo $data['cpu_load']; ?>"><div style="width: <?php echo $data['cpu_load']; ?>"></div></div></div>
			<div class="flex-box"><strong><?php _e('CPU-Temperatur'); ?></strong><span><?php echo $data['cpu_temp']; ?></span></div>
			<div class="flex-box"><strong><?php _e('RAM'); ?></strong><div class="progressbar" data-text="<?php echo $data['ram_percentage']; ?>"><div style="width: <?php echo $data['ram_percentage']; ?>"></div></div></div>
			<div class="flex-box"><strong><?php _e('Speicher belegt'); ?></strong><span><?php echo sizeUnit($data['memory']['used']); ?></span></div>
			<div class="flex-box"><strong><?php _e('Speicher frei'); ?></strong><span><?php echo sizeUnit($data['memory']['free']); ?></span></div>
			<div class="flex-box"><strong><?php _e('Gesamtspeicher'); ?></strong><span><?php echo sizeUnit($data['memory']['total']); ?></span></div>
		</div>
		<div class="inner text-align-center">
			<a class="show-more" href="?s=detailed_overview"><?php _e('Mehr anzeigen'); ?></a>
		</div>
	</div>
</div>
<div class="clear-both"></div>
<?php if (is_array($data['usb_devices'])) { ?>
<div class="order-3">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Angeschlossene Ger&auml;te'); ?></span>
			<?php if ($data['usb_devices_cache_hint'] != NULL) echo $data['usb_devices_cache_hint']; ?>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 100%;"><?php _e('Bezeichnung'); ?></th>
				</tr>
<?php foreach ($data['usb_devices'] as $value) { ?>
				<tr>
					<td><?php echo htmlentities($value); ?></td>
				</tr>
<?php } ?>
			</table>
		</div>
	</div>
</div>
<?php } ?>