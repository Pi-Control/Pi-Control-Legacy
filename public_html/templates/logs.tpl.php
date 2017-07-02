<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Logdateien'); ?></span>
		</div>
<?php if (!$data['sshAvailable']) { ?>
		<div class="inner">
			<strong class="red"><?php _e('Einige Dateien k&ouml;nnen aufgrund mangelnder Berechtigung nicht ge&ouml;ffnet werden. Melde dich per SSH an, um diese ebenfalls zu &ouml;ffnen. <a href="%s">Jetzt anmelden.</a>', '?s=ssh_login'); ?></strong>
		</div>
<?php } ?>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 60%;"><?php _e('Dateiname'); ?></th>
					<th style="width: 15%;"><?php _e('Letzte &Auml;nderung'); ?></th>
					<th style="width: 15%;"><?php _e('Dateigr&ouml;ße'); ?></th>
					<th style="width: 10%;"></th>
				</tr>
				<?php foreach ($data['logs'] as $log) { ?>
					<tr>
						<td><?php echo $log->getRelativePath().$log->getName(); if ($log->getCount(true) > 0) echo ' ('.$log->getCount(true).' '._t('Weitere verf&uuml;gbar').')'; ?></td>
						<td><?php echo formatTime($log->getMain()->getModified()); ?></td>
						<td><?php echo sizeUnit($log->getMain()->getFilesize()); ?></td>
						<td class="table-right"><a <?php if ($log->getMain()->getReadable() === true) echo 'href="?s=logs&amp;view='.urlencode($log->getMain()->getRelativePath()).'"'; ?> class="button button-small<?php if ($log->getMain()->getReadable() === false) echo ' button-disabled'; ?>"><?php _e('Anzeigen'); ?></a></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>