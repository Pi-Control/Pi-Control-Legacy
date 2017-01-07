<?php if (!defined('PICONTROL')) exit(); ?>
<script type="text/javascript"><?php echo $data['jsVariables']; ?></script>
<script type="text/javascript" src="public_html/js/network_connect.wlan.js"></script>
<noscript>
<div>
	<div class="info_red box">
		<div class="inner">
			<strong><?php _e('Bitte aktiviere JavaScript, um dich mit einem WLAN-Netzwerk verbinden zu k&ouml;nnen.'); ?></strong>
		</div>
	</div>
</div>
</noscript>
<!-- Container -->
<div>
	<div class="box">
		<form action="#" method="post">
			<div class="inner-header">
				<span><?php _e('WLAN-Verbindung herstellen'); ?></span>
			</div>
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td><?php _e('Interface'); ?></td>
						<td><input type="hidden" name="interface" value="<?php echo $data['interface']; ?>" /><?php echo $data['interface']; ?></td>
					</tr>
					<tr>
						<td><?php _e('Netzwerkname'); ?></td>
						<td><input type="hidden" name="ssid" value="<?php echo $data['ssid']; ?>" /><?php echo $data['ssid']; ?></td>
					</tr>
<?php if (isset($data['encryption']) && $data['encryption'] == 1) { ?>
					<tr>
						<td><?php _e('Passwort'); ?></td>
						<td><input type="password" name="password" maxlength="64" /></td>
					</tr>
<?php } elseif (isset($data['encryption']) && $data['encryption'] == 2) { ?>
					<tr>
						<td><?php _e('Passwort (falls n&ouml;tig)'); ?></td>
						<td><input type="password" name="password" data="opt" maxlength="64" /></td>
					</tr>
<?php } ?>
				</table>
			</div>
			<div class="inner-end dummy-1">
				<input type="submit" name="submit" value="<?php _e('Verbindung herstellen'); ?>" />
			</div>
			<div class="inner dummy-2 display-none">
				 <span class="svg-network-signal-animate display-inline-block" style="vertical-align: bottom;"></span> <strong><?php _e('Verbindung mit "%s" wird hergestellt...', $data['ssid']); ?>.</strong>
			</div>
			<div class="inner dummy-3 display-none">
				<span class="svg-network-signal-disabled display-inline-block" style="vertical-align: bottom;"></span> <strong><?php _e('Verbindung mit "%s" war nicht erfolgreich! <a href="%s">Erneut versuchen.</a>', $data['ssid'], '#try_again'); ?><br /><br /><span class="red"></span></strong>
			</div>
		</form>
	</div>
</div>