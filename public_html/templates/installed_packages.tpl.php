<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span>Installierte Packete</span>
			<?php showGoBackIcon('?s=detailed_overview'); ?>
		</div>
		<div class="inner">
			<strong>Anzahl an installierten Paketen: <?php echo $data['installedPackagesCount']; ?></strong>
			</table>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th>Paketname</th>
					<th>Version</th>
					<th>Beschreibung</th>
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