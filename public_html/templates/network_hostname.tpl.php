<?php if (!defined('PICONTROL')) exit(); ?>
<!-- Content -->
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Hostname'); ?></span>
			<?php echo showGoBackIcon('?s=network'); ?>
		</div>
		<div class="inner-info">
			<div><?php _e('Bei einer &Auml;nderung des Hostname ist der neue Name erst nach einem Neustart sichtbar.'); ?></div>
		</div>
		<form action="?s=network&amp;hostname" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td><?php _e('Hostname'); ?></td>
						<td><input type="text" name="hostname" value="<?php echo $data['hostname']; ?>" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('Speichern'); ?>" />
			</div>
		</form>
	</div>
</div>