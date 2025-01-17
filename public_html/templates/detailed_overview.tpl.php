<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Detaillierte &Uuml;bersicht'); ?></span>
			<?php showGoBackIcon('?s=overview'); ?>
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
					<td><?php _e('Aktuelle Zeit'); ?></td>
					<td><?php echo $data['time']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Aktuelle Zeitzone'); ?></td>
					<td><?php echo $data['timezone']; ?></td>
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
			<span class="subtitle"><?php _e('System'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form responsive-detailed-overview-table">
				<tr>
					<td><?php _e('Laufende Prozesse'); ?></td>
					<td><?php echo $data['runningTasksCount']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Installierte Pakete'); ?></td>
					<td><?php echo $data['installedPackagesCount']; ?> <a href="?s=installed_packages">(<?php _e('Pakete auflisten'); ?>)</a></td>
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
					<td><?php _e('Auslastung Gesamt'); ?></td>
					<td style="padding: 6px 15px 6px 7px;"><div class="progressbar" data-text="<?php echo $data['cpu_load']; ?>%" style="max-width: 250px;"><div style="width: <?php echo $data['cpu_load']; ?>%;"></div></div></td>
				</tr>
<?php foreach ($data['cpu_loads'] as $name => $value) { ?>
				<tr>
					<td><?php _e('Auslastung %s', $name); ?></td>
					<td style="padding: 6px 15px 6px 7px;"><div class="progressbar" data-text="<?php echo $value; ?>%" style="max-width: 250px;"><div style="width: <?php echo $value; ?>%;"></div></div></td>
				</tr>
<?php } ?>
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
					<td style="padding: 6px 15px 6px 7px;"><div class="progressbar" data-text="<?php echo $data['ram_percentage']; ?>" style="max-width: 250px;"><div style="width: <?php echo $data['ram_percentage']; ?>;"></div></div></td>
				</tr>
			</table>
			<br />
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Gesamtspeicher'); ?></span>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless">
				<tr>
					<th style="width: 20%;"><?php _e('Partition'); ?></th>
					<th style="width: 10%;"><?php _e('Mountpunkt'); ?></th>
					<th style="width: 10%; min-width: 70px;"><?php _e('Belegt'); ?></th>
					<th style="width: 10%; min-width: 70px;"><?php _e('Frei'); ?></th>
					<th style="width: 10%; min-width: 70px;"><?php _e('Gesamt'); ?></th>
					<th style="width: 40%; min-width: 150px;"></th>
				</tr>
<?php $i = 0; foreach ($data['memory'] as $value) {
		if (++$i != $data['memory_count']) { ?>
				<tr>
					<td><?php echo $value['device']; ?></td>
					<td><?php echo $value['mountpoint']; ?></td>
					<td><?php echo sizeUnit($value['used']); ?></td>
					<td><?php echo sizeUnit($value['free']); ?></td>
					<td><?php echo sizeUnit($value['total']); ?></td>
					<td><div class="progressbar" data-text="<?php echo $value['percent']; ?>%"><div style="width: <?php echo $value['percent']; ?>%;"></div></div></td>
				</tr>
<?php } else { ?>
				<tr style="font-weight: bold;" class="background-color-2">
					<td><?php _e('Gesamt'); ?></td>
					<td></td>
					<td><?php echo sizeUnit($value['used']); ?></td>
					<td><?php echo sizeUnit($value['free']); ?></td>
					<td><?php echo sizeUnit($value['total']); ?></td>
					<td style="font-weight: normal;"><div class="progressbar" data-text="<?php echo $value['percent']; ?>%"><div style="width: <?php echo $value['percent']; ?>%;"></div></div></td>
				</tr>
<?php }
} ?>
			</table>
		</div>
	</div>
</div>