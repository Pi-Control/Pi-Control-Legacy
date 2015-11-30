<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zu Test'); ?></span>
			<?php showGoBackIcon('?s=plugins&amp;id=test'); ?>
		</div>
		<div class="inner">
			<?php _e('Dies ist ein Test-Plugin zum besseren Verst&auuml;ndnis des Aufbaus.'); ?>
		</div>
		<form action="?s=plugins&amp;id=test&amp;settings" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Checkbox</td>
						<td><input type="checkbox" name="checkbox" value="checked" id="cb"<?php if ($data['configCheckbox'] == true) echo ' checked="checked"'; ?> /><label for="cb" class="checkbox only-checkbox">&nbsp;</label> <span class="small-info">Nur ein Infotext</span></td>
					</tr>
					<tr>
						<td>Textfeld</td>
						<td><input type="text" name="text" value="<?php echo $data['configText']; ?>" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="Speichern" />
			</div>
		</form>
	</div>
</div>