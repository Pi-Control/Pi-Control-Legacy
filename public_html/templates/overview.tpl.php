<script type="text/javascript">{$js_variables}</script>
<script type="text/javascript" src="public_html/js/overview_status_reload.js"></script>
<!-- Sidebar -->
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('System'); ?></span>
		</div>
		<div class="inner">
			<a href="?action=system_restart" onClick="return ((confirm('<?php _e('Willst du deinen Raspberry Pi wirklich neustarten?'); ?>') == false) ? false : true);"><button class="system_restart"><?php _e('Neustarten'); ?></button></a><a href="?action=system_shutdown" onClick="return ((confirm('<?php _e('Willst du deinen Raspberry Pi wirklich herunterfahren?'); ?>') == false) ? false : true);"><button class="system_shutdown"><?php _e('Herunterfahren'); ?></button></a>
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
		<div class="inner">
			<table class="table _status">
				<tr>
					<th><?php _e('Information'); ?></th>
					<th colspan="2"><?php _e('Wert'); ?> <a href="#reload" title="<?php _e('Aktualisieren'); ?>"><img src="public_html/img/refresh.png" class="overview_status_refresh" width="12" alt="<?php _e('Aktualisieren'); ?>" /></a></th>
				</tr>
				<tr>
					<td class="width-50"><?php _e('Laufzeit'); ?> / <?php _e('Startzeit'); ?></td>
					<td class="width-25"><?php echo $data['run_time']; ?></td>
					<td class="width-25"><?php echo $data['start_time']; ?></td>
				</tr>
				<tr>
					<td><?php _e('CPU-Takt'); ?></td>
					<td colspan="2"><?php echo $data['cpu_clock']; ?></td>
				</tr>
				<tr>
					<td><?php _e('CPU-Auslastung'); ?></td>
					<td colspan="2" class="padding-0">
						<div class="overview_status_td">
							<div class="progress">
								<div style="width: <?php echo $data['cpu_load']; ?>;"></div>
								<div><?php echo $data['cpu_load']; ?></div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td><?php _e('CPU-Typ'); ?></td>
					<td colspan="2"><?php echo $data['cpu_type']; ?></td>
				</tr>
				<tr>
					<td><?php _e('CPU-Temperatur'); ?></td>
					<td colspan="2"><?php echo $data['cpu_temp']; ?></td>
				</tr>
				<tr>
					<td><?php _e('RAM'); ?></td>
					<td colspan="2" class="padding-0">
						<div class="overview_status_td">
							<div class="progress">
								<div style="width: <?php echo $data['ram_percentage']; ?>;"></div>
								<div><?php echo $data['ram_percentage']; ?></div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td><?php _e('Speicher belegt / frei'); ?></td>
					<td><?php echo sizeUnit($data['memory']['used']); ?></td>
					<td><?php echo sizeUnit($data['memory']['free']); ?></td>
				</tr>
				<tr>
					<td><?php _e('Gesamtspeicher'); ?></td>
					<td colspan="2"><?php echo sizeUnit($data['memory']['total']); ?></td>
				</tr>
				<tr>
					<td colspan="3" class="padding-0">
						<div class="overview_status_td">
							<div class="progress">
								<div style="width: <?php echo $data['memory']['percent']; ?>%;"></div>
								<div><?php echo $data['memory']['percent']; ?>%</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<div class="overview_status_reload_bar"></div>
			<div class="overview_display_more">
				<a href="?s=detailed_overview"><?php _e('Mehr anzeigen'); ?></a>
			</div>
		</div>
	</div>
</div>
<div class="clear_both"></div>
<?php if (is_array($data['usb_devices'])) { ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Angeschlossene Geräte'); ?></span>
		</div>
		<div class="inner">
			<table class="table">
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