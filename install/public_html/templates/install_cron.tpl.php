<?php if (!defined('PICONTROL')) exit(); ?>
<style>
@-webkit-keyframes move {
    0% {
       width: 60%;
    }
    30% {
       width: 80%;
    }
}
</style>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Fortschritt'); ?></span>
		</div>
		<div class="inner">
			<div class="progressbar"><div style="width: 80%; -webkit-animation: move 1.5s linear forwards;">&nbsp;</div></div>
		</div>
		<div class="inner text-center">
			<strong>80%</strong>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('4. Schritt'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Um das Pi Control zu automatisieren und regelm&auml;ßig Aufgaben im Hintergrund ausf&uuml;hren zu k&ouml;nnen, wird ein Cron ben&ouml;tigt. Nachfolgend kann dieser automatisch erstellt werden oder aber du <a href="%s">&uuml;berspringst diesen Schritt</a> und legst den Cron sp&auml;ter von Hand ein.', '?s=install_finish'); ?>
		</div>
	</div>
</div>
<div class="clear-both"></div>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('SSH-Login'); ?></span>
		</div>
		<div class="inner-info">
			<div><?php _e('Deine Daten werden f&uuml;r diese Funtkion nicht gespeichert und nur zum einmaligen Eintragen eines Crons in /etc/crontab ben&ouml;tigt.'); ?></div>
		</div>
		<form action="?s=install_cron" method="post">
			<input type="radio" id="ssh-login-passwd" name="ssh-login" value="password" checked="checked" />
			<label for="ssh-login-passwd">
				<div class="inner-table">
					<div class="ssh-login-table-clickable-area"></div>
					<table class="table table-borderless">
						<tr>
							<th colspan="2"><strong><?php _e('Anmeldung &uuml;ber ein Passwort'); ?></strong><span> [<?php _e('Klicken zum aktivieren'); ?>]</span></th>
						</tr>
						<tr>
							<td style="width: 30%;"><strong><?php _e('SSH-Port'); ?></strong></td>
							<td><input type="text" name="port" style="width: 50px;" value="<?php echo $data['port']; ?>" maxlength="5" /> <span class="small-info"><?php _e('Standard: %d', 22); ?></span></td>
						</tr>
						<tr>
							<td><strong><?php _e('SSH-Benutzername'); ?></strong></td>
							<td><input type="text" name="username" style="width: 40%;" value="<?php echo $data['username']; ?>" /></td>
						</tr>
						<tr>
							<td><strong><?php _e('SSH-Passwort'); ?></strong></td>
							<td><input type="password" name="password" style="width: 40%;" /></td>
						</tr>
					</table>
				</div>
			</label>
			<div class="inner">
				<div class="divider"><div></div><div><?php _e('ODER'); ?></div></div>
			</div>
			<input type="radio" id="ssh-login-pubkey" name="ssh-login" value="publickey" />
			<label for="ssh-login-pubkey">
				<div class="inner-table">
					<div class="ssh-login-table-clickable-area"></div>
					<table class="table table-borderless">
						<tr>
							<th colspan="2"><strong><?php _e('Anmeldung &uuml;ber einen Publickey'); ?></strong><span> [<?php _e('Klicken zum aktivieren'); ?>]</span></th>
						</tr>
						<tr>
								<td style="width: 30%;"><strong><?php _e('SSH-Port'); ?></strong></td>
								<td><input type="text" name="port_" style="width: 50px;" value="<?php echo $data['port']; ?>" maxlength="5" /> <span class="small-info"><?php _e('Standard: 22'); ?></span></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Benutzername'); ?></strong></td>
								<td><input type="text" name="username_" style="width: 40%;" value="<?php echo $data['username']; ?>" /></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Privatekey'); ?></strong></td>
								<td><textarea name="privatekey_" style="width: 80%; height: 100px;"></textarea></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Passwort (falls n&ouml;tig)'); ?></strong></td>
								<td><input type="password" name="password_" style="width: 40%;" /></td>
							</tr>
					</table>
				</div>
			</label>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('Anmelden und Cron anlegen'); ?>" />
			</div>
		</form>
	</div>
</div>