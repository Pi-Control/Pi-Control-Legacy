<?php if (!defined('PICONTROL')) exit(); ?>
<!-- Content -->
<div>
	<div class="box">
		<div class="inner-header">
			<span>Hostname</span>
			<?php echo showGoBackIcon('?s=network'); ?>
		</div>
		<div class="inner-info">
			<div>Bei einer &Auml;nderung des Hostname ist der neue Name erst nach einem Neustart sichtbar.</div>
		</div>
		<form action="?s=network&amp;hostname" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Hostname</td>
						<td><input type="text" name="hostname" value="<?php echo $data['hostname']; ?>" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="Speichern" />
			</div>
		</form>
	</div>
</div>