<style>
@-webkit-keyframes move {
    0% {
       width: 50%;
    }
    30% {
       width: 75%;
    }
}
</style>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Fortschritt'); ?></span>
		</div>
		<div class="inner">
			<div class="progressbar"><div style="width: 75%; -webkit-animation: move 1.5s linear forwards;">&nbsp;</div></div>
		</div>
		<div class="inner text-center">
			<strong>75%</strong>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('3. Schritt'); ?></span>
		</div>
		<div class="inner">
			<?php _e('In diesem Schritt kannst du einen Benutzer f&uuml;r das Pi Control erstellen. Dieser Benutzer hat nichts mit dem SSH-Login zu tun und wird nur zur Anmeldung an das Pi Control genutzt.'); ?>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Benutzer erstellen'); ?></span>
		</div>
		<form action="?s=install_user" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td style="width: 40%;"><?php _e('Benutzername'); ?></td>
						<td><input type="text" name="username" maxlength="32" /></td>
					</tr>
					<tr>
						<td><?php _e('Passwort'); ?></td>
						<td><input type="password" name="password" maxlength="64" /></td>
					</tr>
					<tr>
						<td><?php _e('Passwort wiederholen'); ?></td>
						<td><input type="password" name="password2" maxlength="64" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('N&auml;chster Schritt'); ?>" />
			</div>
		</form>
	</div>
</div>