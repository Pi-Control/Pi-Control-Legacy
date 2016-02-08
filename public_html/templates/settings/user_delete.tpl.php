<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span>Einstellungen zum Benutzer - Benutzer l&ouml;schen</span>
			<?php showGoBackIcon('?s=settings&amp;do=user'); ?>
		</div>
		<form action="?s=settings&amp;do=user&amp;delete=<?php echo $data['lowerUsername']; ?>" method="post">
			<div class="inner">
				Bitte gebe zur Best&auml;tigung das Passwort des Benutzers an.
			</div>
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Benutzername</td>
						<td><?php echo $data['username']; ?></td>
					</tr>
					<tr>
						<td>Passwort</td>
						<td><input type="password" name="password" maxlength="64" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="L&ouml;schen" />
			</div>
		</form>
	</div>
</div>