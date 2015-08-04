<!-- Container -->
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('SSH-Login'); ?></span>
		</div>
<?php if ($data['logged_in'] === false) { ?>
		<form action="?s=ssh_login" method="post">
			<div class="inner-bottom">
				<strong class="red"><?php _e('Du bist noch nicht angemeldet. Dadurch kannst du einige Funktionen nicht nutzen.'); ?></strong>
			</div>
			<style>
				input[name=ssh-login] {
					display: none;
				}
				
				input[name=ssh-login] + label .inner-bottom table, input[name=ssh-login] + label .inner table {
					opacity: 0.3;
				}
				
				input[name=ssh-login]:checked + label .inner-bottom table, input[name=ssh-login]:checked + label .inner table {
					opacity: 1;
				}
				
				input[name=ssh-login]:checked + label .inner-bottom strong + span, input[name=ssh-login]:checked + label .inner strong + span {
					display: none;
				}
				
				input[name=ssh-login]:checked + label .inner-bottom .dummy, input[name=ssh-login]:checked + label .inner .dummy {
					display: none;
				}
				
				.dummy {
					position: absolute;
					top: 0px;
					right: 0px;
					bottom: 0px;
					left: 0px;
					z-index: 10;
				}
			</style>
			<input type="radio" id="ssh-login-passwd" name="ssh-login" checked="checked" />
			<label for="ssh-login-passwd">
				<div class="inner" style="position: relative;">
					<div class="dummy"></div>
					<?php _e('<strong>Anmeldung über ein Passwort</strong><span> [Klicken zum aktivieren]</span>'); ?><br /><br />
					<table class="table_simple">
						<tr>
								<td style="width: 30%;"><strong><?php _e('SSH-Port'); ?></strong></td>
								<td><input type="text" name="port" style="width: 50px;" value="<?php echo $data['ssh_info']['port']; ?>" maxlength="5" /> <span class="small_info"><?php _e('Standard: 22'); ?></span></td>
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
								<td><input type="checkbox" name="save_passwd" value="checked" /> <span class="small_info"><?php _e('Speichert das Passwort, damit nicht nach jeder Session neu angemeldet werden muss.'); ?></span></td>
					</table>
				</div>
			</label>
			<div class="inner">
				<div style="position: relative; width: 70%; height: 16px; margin: 0px auto 0px;"><div style="position: absolute; background: #E1E1E1; height: 2px; top: 50%; margin-top: -1px; width: 100%; z-index: 5;"></div><div style="background: #FFFFFF; z-index: 10; position: absolute; height: 16px; top: 50%;  margin-top: -8px; left: 50%; margin-left: -30px; width: 60px; text-align: center;"><?php _e('ODER'); ?></div></div>
			</div>
			<input type="radio" id="ssh-login-pubkey" name="ssh-login" />
			<label for="ssh-login-pubkey">
				<div class="inner-bottom" style="position: relative;">
					<div class="dummy"></div>
					<?php _e('<strong>Anmeldung über einen Publickey</strong><span> [Klicken zum aktivieren]</span>'); ?><br /><br />
					<table class="table_simple">
						<tr>
								<td style="width: 30%;"><strong><?php _e('SSH-Port'); ?></strong></td>
								<td><input type="text" name="port_" style="width: 50px;" value="<?php echo $data['ssh_info']['port']; ?>" maxlength="5" /> <span class="small_info"><?php _e('Standard: 22'); ?></span></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Benutzername'); ?></strong></td>
								<td><input type="text" name="username_" style="width: 40%;" value="<?php echo $data['ssh_info']['username']; ?>" /></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Publickey'); ?></strong></td>
								<td><input type="password" name="password_" style="width: 40%;" /></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Privatekey'); ?></strong></td>
								<td><input type="password" name="password_" style="width: 40%;" /></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Passphrase'); ?></strong></td>
								<td><input type="password" name="password_" style="width: 40%;" /></td>
							</tr>
							<tr>
								<td><strong><?php _e('SSH-Login speichern?'); ?></strong></td>
								<td><input type="checkbox" name="save_passwd_" value="checked" /> <span class="small_info"><?php _e('Speichert das Passwort, damit nicht nach jeder Session neu angemeldet werden muss.'); ?></span></td>
					</table>
				</div>
			</label>
			<div class="inner">
				<input type="submit" name="submit" value="<?php _e('Anmelden'); ?>" />
			</div>
		</form>
<?php } else { ?>
		<div class="inner-bottom">
			<strong class="green"><?php _e('Du bist bereits angemeldet mit dem Benutzer %s.', $data['ssh_info']['username']); ?></strong>
		</div>
		<div class="inner">
			<a href="?s=ssh_login&amp;logout"><button><?php _e('Abmelden'); ?></button></a>
		</div>
<?php } ?>
	</div>
</div>
<div class="clear_both"></div>