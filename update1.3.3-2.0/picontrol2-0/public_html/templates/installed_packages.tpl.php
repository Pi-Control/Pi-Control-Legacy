<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Installierte Pakete'); ?></span>
			<?php showGoBackIcon('?s=detailed_overview'); ?>
		</div>
		<div class="inner">
			<strong><?php _e('Anzahl an installierten Paketen: %s', $data['installedPackagesCount']); ?></strong>
			</table>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless">
				<tr>
					<th><?php _e('Paketname'); ?></th>
					<th><?php _e('Version'); ?></th>
					<th><?php _e('Beschreibung'); ?></th>
				</tr>
<?php foreach ($data['installedPackages'] as $package) { ?>
				<tr>
					<td><?php echo $package[1]; ?></td>
					<td><?php echo $package[2]; ?></td>
					<td><?php echo $package[4]; ?></td>
				</tr>
<?php } ?>
			</table>
		</div>
	</div>
</div>