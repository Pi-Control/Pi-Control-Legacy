<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Prozesse'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form">
				<tr>
					<td><?php _e('Anzahl aller Prozesse'); ?></td>
					<td><?php echo $data['processCount']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Anzahl aktiver Prozesse'); ?></td>
					<td><?php echo $data['processCountRunning']; ?></td>
				</tr>
			</table>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless">
				<tr>
					<th style="width: 8%;"><?php _e('PID'); ?></th>
					<th style="width: 10%;"><?php _e('Benutzer'); ?></th>
					<th style="width: 12%;"><?php _e('Status'); ?></th>
					<th style="width: 10%;"><?php _e('CPU'); ?></th>
					<th style="width: 10%;"><?php _e('RAM'); ?></th>
					<th style="width: 10%;"><?php _e('Laufzeit'); ?></th>
					<th style="width: 20%;"><?php _e('Befehl'); ?></th>
					<th style="width: 20%;"></th>
				</tr>
				<?php foreach ($data['processes'] as $process) { ?>
					<tr>
						<td><?php echo $process->getPid(); ?></td>
						<td><?php echo $process->getUser(); ?></td>
						<td><?php echo getReadableStatus($process->getStatus()); ?></td>
						<td><?php echo numberFormat($process->getCpu()); ?>%</td>
						<td><?php echo numberFormat($process->getRam()); ?>%</td>
						<td><?php echo $process->getRuntime(); ?></td>
						<td><?php echo $process->getCommand(); ?></td>
						<td><a class="button button-small">Beenden</a><a class="button button-small">Beenden</a></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>