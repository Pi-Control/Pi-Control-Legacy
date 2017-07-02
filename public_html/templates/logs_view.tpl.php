<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Logdateien - Ansicht'); ?></span>
			<?php showGoBackIcon('?s=logs'); ?>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form">
				<tr>
					<td><?php _e('Dateiname'); ?></td>
					<td><?php echo $data['log']->getFilename(); ?></td>
				</tr>
				<tr>
					<td><?php _e('Letzte &Auml;nderung'); ?></td>
					<td><?php echo formatTime($data['log']->getModified()); ?></td>
				</tr>
				<tr>
					<td><?php _e('Dateigr&ouml;ße'); ?></td>
					<td><?php echo sizeUnit($data['log']->getFilesize()); ?></td>
				</tr>
				<tr>
					<td><?php _e('Herunterladen'); ?></td>
					<td><a href="?s=logs&amp;download=<?php echo urlencode($data['log']->getRelativePath()); ?>" class="button button-small">TXT</a></td>
				</tr>
				<?php if ($data['log']->getLogGroup()->getCount() > 1) { ?>
				<tr>
					<td><?php _e('Weitere Dateien'); ?></td>
					<td><form action="?s=logs" method="post">
							<input type="hidden" name="relative_path" value="<?php echo $data['log']->getLogGroup()->getRelativePath(); ?>" />
							<select name="filename">
				<?php foreach ($data['log']->getLogGroup()->getAll() as $log) { ?>
								<option value="<?php echo $log->getFilename(); ?>"<?php if ($log->getFilename() == $data['log']->getFilename()) echo ' disabled="disabled"'; ?>><?php echo $log->getFilename().'&nbsp;&nbsp;&nbsp;&nbsp;('.sizeUnit($log->getFilesize()).')'; ?></option>
				<?php } ?>
							</select> <input type="submit" name="open_file" value="<?php _e('&Ouml;ffnen'); ?>" />
						</form></td>
				</tr>
				<?php } ?>
			</table>
		</div>
<?php if (isset($data['filesizeError'])) { ?>
		<div class="inner">
			<strong class="red"><?php _e('Leider kann die angeforderte Datei, aufgrund ihrer Dateigr&ouml;ße (&lt; %s), nicht ge&ouml;ffnet werden.', '10 MB'); ?></strong>
		</div>
<?php } else { ?>
		<div class="inner padding-0 log-view">
			<div class="log-view-outer">
				<div class="log-view-line_numbers">
					1<br />
					<?php for ($i = 2; $i <= $data['logLines']; $i++) echo $i.'<br />'; ?>
				</div>
				<div class="log-view-text">
					<?php echo nl2br(htmlspecialchars($data['logOutput'])); ?>
				</div>
			</div>
		</div>
<?php } ?>
	</div>
</div>