<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Cache'); ?></span>
			<?php showGoBackIcon('?s=settings'); ?>
		</div>
		<form action="?s=settings&amp;do=cache" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Aktivieren</td>
						<td><input type="checkbox" name="activation" value="checked" id="cb-activiation"<?php if ($data['cache-activation'] === true) echo ' checked="checked"'; ?> /><label for="cb-activiation" class="checkbox only-checkbox">&nbsp;</label>
					</tr>
				</table>
			</div>
			<div class="inner-table overflow-auto">
				<table class="table table-borderless">
					<tr>
						<th style="width: 5%;"></th>
						<th style="width: 30%;">Anwendung</th>
						<th style="width: 20%;" class="table-center">Gr&ouml;ße</th>
						<th style="width: 20%;" class="table-center">Letzte &Auml;nderung</th>
						<th style="width: 20%;" class="table-center">Speicherzeit</th>
						<th style="width: 5%;"></th>
					</tr>
<?php foreach ($data['cache-files'] as $name => $info) { ?>
					<tr>
						<td><input type="checkbox" name="cb-<?php echo $name; ?>" value="checked" id="cb-<?php echo $name; ?>"<?php if (isset($info['active']) && $info['active'] === true) echo ' checked="checked"'; ?> /><label for="cb-<?php echo $name; ?>" class="checkbox only-checkbox">&nbsp;</label></td>
						<td><?php echo getCacheName($name); ?></td>
						<td class="table-center"><?php echo (isset($info['filesize'])) ? sizeUnit($info['filesize']) : '-'; ?></td>
						<td class="table-center"><?php echo (isset($info['modification'])) ? formatTime($info['modification']) : '-'; ?></td>
						<td class="table-center"><input type="text" name="text-<?php echo $name; ?>" maxlength="4" style="width: 40px !important;" value="<?php echo (isset($info['lifetime'])) ? $info['lifetime'] : '0'; ?>" /> Min.</td>
						<td><a href="?s=settings&amp;do=cache&amp;clear=<?php echo $name; ?>" class="button button-small">Leeren</a></td>
					</tr>
<?php } ?>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="Speichern" />
			</div>
		</form>
	</div>
</div>