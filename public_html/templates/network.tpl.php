<!-- Sidebar -->
<div class="sidebar order-2">
	<div class="box">
		<div class="inner-navi">
			<a href="?s=network"><?php _e('Übersicht'); ?></a>
			<a href="?s=network_configuration"><?php _e('Konfiguration'); ?></a>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Traffic'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 20%;"><?php _e('Interface'); ?></th>
					<th style="width: 40%;"><?php _e('Gesendet'); ?></th>
					<th style="width: 40%;"><?php _e('Empfangen'); ?></th>
				</tr>
<?php foreach ($data['network_connections'] as $value) { ?>
				<tr>
					<td><?php echo $value['interface']; ?></td>
					<td><?php echo sizeUnit($value['sent']); ?></td>
					<td><?php echo sizeUnit($value['receive']); ?></td>
				</tr>
<?php } ?>
			</table>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Hostname'); ?></span>
		</div>
		<div class="inner justify">
			<?php _e('Dein Raspberry Pi wird im Netzwerk unter folgendem Namen angezeigt: <strong>%s</strong>', $data['hostname']); ?>
		</div>
		<div class="inner-end">
			<a href="?s=network&amp;hostname"><button><?php _e('Ändern'); ?></button></a>
		</div>
	</div>
</div>
<!-- Container -->
<div class="container-600 order-1">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Netzwerk'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
					<tr>
						<th style="width: 2%;"></th>
						<th style="width: 10%;"><?php _e('Interface'); ?></th>
						<th style="width: 44%;"><?php _e('IP'); ?></th>
						<th style="width: 44%;"><?php _e('MAC-Adresse'); ?></th>
					</tr>
<?php foreach ($data['network_connections'] as $value) { ?>
					<tr>
						<td>
	<?php if (isset($value['option']['signal'])) { echo getImageFromSignal($value['option']['signal']); } else {
			if ($value['ip'] != 0) { ?>
							<span class="svg-network-signal-wire"></span>
		<?php } else { ?>
							<span class="svg-network-signal-disabled"></span>
	<?php } } ?></td>
						<td><?php echo $value['interface']; ?></td>
						<td><?php if ($value['ip'] != 0) { ?><a href="http://<?php echo $value['ip']; ?>" target="_blank"><?php echo $value['ip']; ?></a><?php } else { ?><?php _e('Nicht verbunden'); ?><?php } ?></td>
						<td><?php echo $value['mac']; ?></td>
					</tr>
<?php } ?>
				</table>
		</div>
	</div>
<?php foreach ($data['wlan'] as $key => $value) { ?>
	<div class="box">
		<div class="inner-header">
			<span><?php echo $key; ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless responsive-network-wlan-table">
				<tr>
					<th style="width: 1%;"></th>
					<th style="width: 52%;"><?php _e('Netzwerkname'); ?></th>
					<th style="width: 25%;"><?php _e('MAC-Adresse'); ?></th>
					<th style="width: 17%;"><?php _e('Sicherheit'); ?></th>
					<th style="width: 5%;"><?php _e('Kanal'); ?></th>
				</tr>
	<?php foreach ($value as $value2) { ?>
				<tr>
					<td title="<?php echo $value2['signal']; ?>%"><?php echo getImageFromSignal($value2['signal']); ?></td>
					<td><a href="?s=network_connect&amp;interface=<?php echo $key; ?>&amp;ssid=<?php echo $value2['ssid']; ?>&amp;encryption=<?php echo (($value['encryption'] == '-') ? '2' : '1' ) ?>"><?php echo $value2['ssid']; ?></a></td>
					<td><?php echo $value2['mac']; ?></td>
					<td><?php echo $value2['encryption']; ?></td>
					<td class="text-align-center"><?php echo $value2['channel']; ?></td>
				</tr>
	<?php } if (count($value) == 0) { ?>
				<tr>
					<td colspan="5"><strong class="red"><?php _e('Keine WLAN-Netzwerke gefunden. <a href="?s=network&amp;refresh_wlan=%s">Erneut suchen.</a>', $key); ?></strong></td>
				</tr>
	<?php } ?>
			</table>
		</div>
	</div>
<?php } ?>
</div>
<div class="clear-both"></div>