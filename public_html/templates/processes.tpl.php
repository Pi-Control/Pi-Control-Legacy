<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Prozesse'); ?></span>
		</div>
<?php if (!$data['sshAvailable']) { ?>
			<div class="inner">
				<strong class="red">Aufgrund mangelnder Berechtigungen k&ouml;nnen Prozesse nicht beendet werden. Melde dich per SSH an, um Prozesse zu beenden. <a href="?s=ssh_login">Jetzt anmelden.</a></strong>
			</div>
<?php } ?>
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
			<table class="table table-borderless process-table-min-width-text-overflow">
				<tr>
					<th style="width: 8%;"><?php _e('PID'); ?></th>
					<th style="width: 8%;"><?php _e('Benutzer'); ?></th>
					<th style="width: 10%;"><?php _e('Status'); ?></th>
					<th style="width: 8%;"><?php _e('CPU'); ?></th>
					<th style="width: 8%;"><?php _e('RAM'); ?></th>
					<th style="width: 15%;"><?php _e('Startzeitpunkt'); ?></th>
					<th style="width: 10%;"><?php _e('Laufzeit'); ?></th>
					<th style="width: 18%;"><?php _e('Befehl'); ?></th>
					<th style="width: 15%;"></th>
				</tr>
				<?php foreach ($data['processes'] as $process) { ?>
					<tr>
						<td><?php echo $process->getPid(); ?></td>
						<td><?php echo $process->getUser(); ?></td>
						<td><?php echo getReadableStatus($process->getStatus()); ?></td>
						<td><?php echo numberFormat($process->getCpu()); ?>%</td>
						<td><?php echo numberFormat($process->getRam()); ?>%</td>
						<td><?php echo formatTime(getStartTimeFromTime($process->getElapsedTime())); ?></td>
						<td><?php echo getDateFormat(getSecondsFromTime($process->getRuntime())); ?></td>
						<td title="<?php echo htmlspecialchars($process->getCommand()); ?>"><?php echo htmlspecialchars($process->getCommand()); ?></td>
						<td class="table-right white-space-nowrap"><form action="?s=processes" method="post"><input type="hidden" name="pid" value="<?php echo $process->getPid(); ?>" /><input type="hidden" name="startTime" value="<?php echo getStartTimeFromTime($process->getElapsedTime()); ?>" /><input class="button-small" type="submit" name="terminate" value="Beenden"<?php if (!$data['sshAvailable']) echo ' disabled="disabled"'; ?> /> <input class="button-small" type="submit" name="kill" value="Abw&uuml;rgen"<?php if (!$data['sshAvailable']) echo ' disabled="disabled"'; ?> /></form></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>