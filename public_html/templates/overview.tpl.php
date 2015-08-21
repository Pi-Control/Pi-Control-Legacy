<script type="text/javascript">{$js_variables}</script>
<script type="text/javascript" src="public_html/js/overview.status_refresh.js"></script>
<!-- Sidebar -->
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('System'); ?></span>
		</div>
		<div class="inner">
			<table class="table-overview-system">
				<tr>
					<td><a href="?action=system_restart" onClick="return ((confirm('<?php _e('Willst du deinen Raspberry Pi wirklich neustarten?'); ?>') == false) ? false : true);"><button><?php _e('Neustarten'); ?></button></td>
					<td></td>
					<td><a href="?action=system_shutdown" onClick="return ((confirm('<?php _e('Willst du deinen Raspberry Pi wirklich herunterfahren?'); ?>') == false) ? false : true);"><button class="system_shutdown"><?php _e('Herunterfahren'); ?></button></td>
				</tr>
			</table>
		</div>
	</div>
<?php if ($data['show_weather'] === true) { ?>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Wetter'); ?></span>
		</div>
		<div class="inner">
	<?php if ($data['weather'] === 0) { ?>
			<strong class="red">Die aktuelle Postleitzahl ist ungültig.</strong>
	<?php } elseif ($data['weather'] === 1) { ?>
			<strong class="red">Das Wetter kann aktuell nicht abgerufen werden.</strong>
	<?php } elseif ($data['weather'] === 2) { ?>
			<strong class="red">Wetter wurde noch nicht konfiguriert. <a href="?s=settings&do=overview">Zu den Einstellungen.</a></strong>
	<?php } else { ?>
			<strong>{$weather.city}</strong><br /><br />
			<table style="margin: 0px auto 0px;">
				<tr>
					<td style="width: 100px; text-align: center;">
						<img src="public_html/img/weather/{$weather.icon}.png" title="{$weather.description}" alt="Wetter" /><br />
						<span style="font-size: 11px;" title="Windstärke | Luftfeuchtigkeit">{$weather.wind} km/h | {$weather.humidity} %</span>
					</td>
					<td style="width: 100px; text-align: center;"><span style="font-size: 30px;">
						{$weather.temp} &deg;C</span><br />
						<span style="font-size: 13px;">{$weather.temp_min} &deg;C | {$weather.temp_max} &deg;C</span>
					</td>
				</tr>
			</table>
	<?php } ?>
		</div>
	</div>
<?php } ?>
</div>
<!-- Container -->
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Übersicht'); ?></span>
			<?php showSettingsIcon('?s=settings&amp;do=overview'); ?>
		</div>
		<div class="inner flex-container">
			<div class="flex-box-refresh"><div><div class="refresh-bar"></div></div><a href="#refresh"><img src="public_html/img/refresh-16.png" title="<?php _e('Aktualisieren'); ?>" /></a></div>
			<div class="flex-box"><strong><?php _e('Startzeit'); ?></strong><?php echo $data['start_time']; ?></div>
			<div class="flex-box"><strong><?php _e('Laufzeit'); ?></strong><?php echo $data['run_time']; ?></div>
			<div class="flex-box"><strong><?php _e('CPU-Takt'); ?></strong><?php echo $data['cpu_clock']; ?></div>
			<div class="flex-box"><strong><?php _e('CPU-Auslastung'); ?></strong><div class="progressbar"><div style="width: <?php echo $data['cpu_load']; ?>;"><?php echo $data['cpu_load']; ?></div></div></div>
			<div class="flex-box"><strong><?php _e('CPU-Temperatur'); ?></strong><?php echo $data['cpu_temp']; ?></div>
			<div class="flex-box"><strong><?php _e('RAM'); ?></strong><div class="progressbar"><div style="width: <?php echo $data['ram_percentage']; ?>;"><?php echo $data['ram_percentage']; ?></div></div></div>
			<div class="flex-box"><strong>Speicher belegt</strong><?php echo sizeUnit($data['memory']['used']); ?></div>
			<div class="flex-box"><strong>Speicher frei</strong><?php echo sizeUnit($data['memory']['free']); ?></div>
			<div class="flex-box"><strong><?php _e('Gesamtspeicher'); ?></strong><?php echo sizeUnit($data['memory']['total']); ?></div>
		</div>
		<div class="inner text-align-center">
			<a class="show-more" href="?s=detailed_overview"><?php _e('Mehr anzeigen'); ?></a>
		</div>
	</div>
</div>
<div class="clear-both"></div>
<?php if (is_array($data['usb_devices'])) { ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Angeschlossene Geräte'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 100%;"><?php _e('Bezeichnung'); ?></th>
				</tr>
<?php foreach ($data['usb_devices'] as $value) { ?>
				<tr>
					<td><?php echo $value; ?></td>
				</tr>
<?php } ?>
			</table>
		</div>
	</div>
</div>
<?php } ?>