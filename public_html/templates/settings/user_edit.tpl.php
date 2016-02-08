<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span>Einstellungen zum Benutzer - Benutzer bearbeiten</span>
			<?php showGoBackIcon('?s=settings&amp;do=user'); ?>
		</div>
		<form action="?s=settings&amp;do=user&amp;edit=<?php echo $data['lowerUsername']; ?>" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Benutzername</td>
						<td><?php echo $data['username']; ?></td>
					</tr>
					<tr>
						<td>Altes Passwort</td>
						<td><input type="password" name="passwordOld" maxlength="64" /></td>
					</tr>
					<tr>
						<td>Neues Passwort</td>
						<td><input type="password" name="passwordNew" maxlength="64" /></td>
					</tr>
					<tr>
						<td>Neues Passwort wiederholen</td>
						<td><input type="password" name="passwordNew2" maxlength="64" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="Speichern" />
			</div>
		</form>
	</div>
</div>