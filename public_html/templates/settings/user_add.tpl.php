<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span>Einstellungen zum Benutzer - Benutzer hinzuf&uuml;gen</span>
			<?php showGoBackIcon('?s=settings&amp;do=user'); ?>
		</div>
		<form action="?s=settings&amp;do=user&amp;add" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Benutzername</td>
						<td><input type="text" name="username" maxlength="32" /></td>
					</tr>
					<tr>
						<td>Passwort</td>
						<td><input type="password" name="password" maxlength="64" /></td>
					</tr>
					<tr>
						<td>Passwort wiederholen</td>
						<td><input type="password" name="password2" maxlength="64" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="Hinzuf&uuml;gen" />
			</div>
		</form>
	</div>
</div>