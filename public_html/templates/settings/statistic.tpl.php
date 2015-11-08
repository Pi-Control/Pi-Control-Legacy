<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zur Statistik'); ?></span>
			<?php showGoBackIcon('?s=statistic'); ?>
		</div>
		<div class="inner">
			<?php _e('Blende nicht ben&ouml;tigte Statistiken aus, um die Ladezeiten zu verbessern und um besser den &Uuml;berblick zu behalten.'); ?>
		</div>
		<div class="inner-info">
			<div><?php _e('Statistiken werden, unabh&auml;ngig vom Anzeigestatus, aufgezeichnet.'); ?></div>
		</div>
		<form action="?s=settings&amp;do=statistic" method="post">
			<div class="inner-table">
				<table class="table table-borderless">
					<tr>
						<th style="width: 5%;"></th>
						<th style="width: 75%;"><?php _e('Name'); ?></th>
						<th style="width: 10%;" class="table-center"><?php _e('Download'); ?></th>
						<th style="width: 10%;"></th>
					</tr>
<?php foreach ($data['logArray'] as $value) { ?>
					<tr>
						<td align="center"><input type="checkbox" name="check[]" id="cb<?php echo urlencode($value['log']); ?>" value="<?php echo $value['log']; ?>" <?php if ($value['display'] == 1) echo 'checked="checked"'; ?> /><label class="checkbox only-checkbox" for="cb<?php echo urlencode($value['log']); ?>">&nbsp;</label></td>
						<td><?php echo $value['label']; ?></td>
						<td class="table-center"><a href="?s=settings&amp;do=statistic&amp;download&amp;log=<?php echo urlencode($value['log']); ?>&amp;type=<?php echo urlencode($value['type']); ?>&amp;label=<?php echo urlencode($value['label']); ?>" class="text-decoration-none"><span class="button button-small">CSV</span></a></td>
						<td class="table-right"><a href="?s=settings&amp;do=statistic&amp;reset=<?php echo urlencode($value['log']); ?>" class="text-decoration-none"><span class="button button-small"><?php _e('Zur&uuml;cksetzen'); ?></span></a></td>
					</tr>
<?php } ?>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('Speichern'); ?>" />
			</div>
		</form>
	</div>
</div>