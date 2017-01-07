<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zum Benutzer - Benutzer bearbeiten'); ?></span>
			<?php showGoBackIcon('?s=settings&amp;do=user'); ?>
		</div>
		<form action="?s=settings&amp;do=user&amp;edit=<?php echo $data['lowerUsername']; ?>" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td><?php _e('Benutzername'); ?></td>
						<td><?php echo $data['username']; ?></td>
					</tr>
					<tr>
						<td><?php _e('Altes Passwort'); ?></td>
						<td><input type="password" name="passwordOld" maxlength="64" /></td>
					</tr>
					<tr>
						<td><?php _e('Neues Passwort'); ?></td>
						<td><input type="password" name="passwordNew" maxlength="64" /></td>
					</tr>
					<tr>
						<td><?php _e('Neues Passwort wiederholen'); ?></td>
						<td><input type="password" name="passwordNew2" maxlength="64" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('Speichern'); ?>" />
			</div>
		</form>
	</div>
</div>