<?php if (!defined('PICONTROL')) exit(); ?>
<!-- Container -->
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('SSH-Login'); ?></span>
		</div>
<?php if ($data['logged_in'] === false) { ?>
		<form action="?s=ssh_login" method="post">
			<div class="inner">
				<strong class="red"><?php _e('Du bist noch nicht angemeldet. Dadurch kannst du einige Funktionen nicht nutzen.'); ?></strong>
			</div>
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
							<td><input type="text" name="port" style="width: 50px;" value="<?php echo $data['ssh_info']['port']; ?>" maxlength="5" /> <span class="small-info"><?php _e('Standard: %d', 22); ?></span></td>
						</tr>
						<tr>
							<td><strong><?php _e('SSH-Benutzername'); ?></strong></td>
							<td><input type="text" name="username" style="width: 40%;" value="<?php echo $data['ssh_info']['username']; ?>" /></td>
						</tr>
						<tr>
							<td><strong><?php _e('SSH-Passwort'); ?></strong></td>
							<td><input type="password" name="password" style="width: 40%;" /></td>
						</tr>
						<tr>
							<td><strong><?php _e('SSH-Login speichern?'); ?></strong></td>
							<td><input type="checkbox" name="save_passwd" value="checked" id="ssh-login-passwd-checkbox" /><label for="ssh-login-passwd-checkbox" class="checkbox only-checkbox">&nbsp;</label> <span class="small_info"><?php _e('Speichert das Passwort, damit nicht nach jeder Session neu angemeldet werden muss.'); ?></span></td>
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
								<td><input type="text" name="port_" style="width: 50px;" value="<?php echo $data['ssh_info']['port']; ?>" maxlength="5" /> <span class="small-info"><?php _e('Standard: 22'); ?></span></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Benutzername'); ?></strong></td>
								<td><input type="text" name="username_" style="width: 40%;" value="<?php echo $data['ssh_info']['username']; ?>" /></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Privatekey'); ?></strong></td>
								<td><textarea name="privatekey_" style="width: 80%; height: 100px;"></textarea></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Passwort (falls n&ouml;tig)'); ?></strong></td>
								<td><input type="password" name="password_" style="width: 40%;" /></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Login speichern?'); ?></strong></td>
								<td><input type="checkbox" name="save_passwd_" value="checked" id="ssh-login-pubkey-checkbox" /><label for="ssh-login-pubkey-checkbox" class="checkbox only-checkbox">&nbsp;</label> <span class="small_info"><?php _e('Speichert das Passwort, damit nicht nach jeder Session neu angemeldet werden muss.'); ?></span></td>
							</tr>
					</table>
				</div>
			</label>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('Anmelden'); ?>" />
			</div>
		</form>
<?php } else { ?>
		<div class="inner">
			<strong class="green"><?php _e('Du bist bereits angemeldet mit dem Benutzer %s.', $data['ssh_info']['username']); ?></strong>
		</div>
		<div class="inner-end">
			<a href="?s=ssh_login&amp;logout"><button><?php _e('Abmelden'); ?></button></a>
		</div>
<?php } ?>
	</div>
</div>
<div class="clear_both"></div>