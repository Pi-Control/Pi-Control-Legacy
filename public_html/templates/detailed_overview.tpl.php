<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Detaillierte &Uuml;bersicht'); ?></span>
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Allgemein'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form responsive-detailed-overview-table">
				<tr>
					<td><?php _e('Raspberry Pi Modell'); ?></td>
					<td><?php echo $data['revision']['model']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Hersteller'); ?></td>
					<td><?php echo $data['revision']['manufacturer']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Revision'); ?></td>
					<td><?php echo $data['revision']['revision']; ?></td>
				</tr>
				<tr>
					<td><?php _e('PCB Revision (Bauserie)'); ?></td>
					<td><?php echo $data['revision']['pcb']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Seriennummer'); ?></td>
					<td><?php echo $data['serial']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Laufzeit'); ?></td>
					<td><?php echo $data['run_time']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Letzter Start'); ?></td>
					<td><?php echo $data['start_time']; ?></td>
				</tr>
			</table>
			<br />
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Software'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form responsive-detailed-overview-table">
				<tr>
					<td><?php _e('Distribution'); ?></td>
					<td><?php echo $data['distribution']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Kernel'); ?></td>
					<td><?php echo $data['kernel']; ?></td>
				</tr>
			</table>
			<br />
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Webserver'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form responsive-detailed-overview-table">
				<tr>
					<td><?php _e('HTTP-Server'); ?></td>
					<td><?php echo $data['webserver']; ?></td>
				</tr>
				<tr>
					<td><?php _e('PHP-Version'); ?></td>
					<td><?php echo $data['php']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Benutzer'); ?></td>
					<td><?php echo $data['whoami']; ?></td>
				</tr>
			</table>
			<br />
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Prozessor'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form responsive-detailed-overview-table">
				<tr>
					<td><?php _e('Takt'); ?></td>
					<td><?php echo $data['cpu_clock']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Maximaler Takt'); ?></td>
					<td><?php echo $data['cpu_max_clock']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Auslastung'); ?></td>
					<td style="padding: 6px 15px 6px 7px;"><div class="progressbar" style="max-width: 250px;"><div style="width: <?php echo $data['cpu_load']; ?>;"><?php echo $data['cpu_load']; ?></div></div></td>
				</tr>
				<tr>
					<td><?php _e('Typ'); ?></td>
					<td><?php echo $data['cpu_type']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Modell'); ?></td>
					<td><?php echo $data['cpu_model']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Temperatur'); ?></td>
					<td><?php echo $data['cpu_temp']; ?></td>
				</tr>
			</table>
			<br />
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Arbeitsspeicher'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form responsive-detailed-overview-table">
				<tr>
					<td><?php _e('Gesamt'); ?></td>
					<td><?php echo $data['revision']['memory']; ?> MB</td>
				</tr>
				<tr>
					<td><?php _e('Auslastung'); ?></td>
					<td style="padding: 6px 15px 6px 7px;"><div class="progressbar" style="max-width: 250px;"><div style="width: <?php echo $data['ram_percentage']; ?>;"><?php echo $data['ram_percentage']; ?></div></div></td>
				</tr>
			</table>
			<br />
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Gesamtspeicher'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 20%;"><?php _e('Partition'); ?></th>
					<th style="width: 10%;"><?php _e('Mountpunkt'); ?></th>
					<th style="width: 10%;"><?php _e('Belegt'); ?></th>
					<th style="width: 10%;"><?php _e('Frei'); ?></th>
					<th style="width: 10%;"><?php _e('Gesamt'); ?></th>
					<th style="width: 40%;"></th>
				</tr>
<?php $i = 0; foreach ($data['memory'] as $value) {
		if (++$i != $data['memory_count']) { ?>
				<tr>
					<td><?php echo $value['device']; ?></td>
					<td><?php echo $value['mountpoint']; ?></td>
					<td><?php echo sizeUnit($value['used']); ?></td>
					<td><?php echo sizeUnit($value['free']); ?></td>
					<td><?php echo sizeUnit($value['total']); ?></td>
					<td><div class="progressbar"><div style="width: <?php echo $value['percent']; ?>%;"><?php echo $value['percent']; ?>%</div></div></td>
				</tr>
<?php } else { ?>
				<tr style="background: #A6C5E8; font-weight: bold;">
					<td><?php _e('Gesamt'); ?></td>
					<td></td>
					<td><?php echo sizeUnit($value['used']); ?></td>
					<td><?php echo sizeUnit($value['free']); ?></td>
					<td><?php echo sizeUnit($value['total']); ?></td>
					<td><div class="progressbar"><div style="width: <?php echo $value['percent']; ?>%;"><?php echo $value['percent']; ?>%</div></div></td>
				</tr>
<?php }
} ?>
			</table>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Alle Benutzer'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 60%;"><?php _e('Benutzername'); ?></th>
					<th style="width: 8%;" class="table-center"><?php _e('Port'); ?></th>
					<th style="width: 17%;" class="table-center"><?php _e('Letzte Anmeldung'); ?></th>
					<th style="width: 15%;" class="table-center"><?php _e('Von'); ?></th>
				</tr>
<?php foreach ($data['all_users'] as $value) { ?>
				<tr>
					<td><?php if ($value['isLoggedIn'] === true) { ?><strong class="green">[<?php _e('Angemeldet'); ?>] </strong><?php } echo $value['username']; if ($value['isLoggedIn'] === true) { ?><div style="color: #666666; margin-top: 3px; margin-left: 20px;"><?php foreach ($value['loggedIn'] as $value2) { _e('An %s am %s um %s von %s', $value2['port'], formatTime($value2['lastLogin'], 'd.m.Y'), formatTime($value2['lastLogin'], 'H:i'), '<a href="http://'.$value2['lastLoginAddress'].'" target="_blank">'.$value2['lastLoginAddress'].'</a>'); ?><br /><?php } ?></div><?php } ?></td>
					<td valign="top" class="table-center"><?php echo $value['port']; ?></td>
					<td valign="top" class="table-center"><?php if ($value['lastLogin'] == 0) { _e('Noch nie angemeldet'); } else { echo formatTime($value['lastLogin']); } ?></td>
					<td valign="top" class="table-center"><a href="http://<?php echo $value['lastLoginAddress']; ?>" target="_blank"><?php echo $value['lastLoginAddress']; ?></a></td>
				</tr>
<?php } ?>
			</table>
		</div>
	</div>
</div>