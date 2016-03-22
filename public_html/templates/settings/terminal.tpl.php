<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zum Terminal'); ?></span>
			<?php showGoBackIcon('?s=terminal'); ?>
		</div>
		<div class="inner">
			Die folgenden Einstellungen erlauben es dir, das Terminal auf bestimme IP-Adressen einzuschr&auml;nken, sodass der Zugriff nur von diesen erlaubt ist.
		</div>
		<form action="?s=settings&amp;do=terminal" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>IP-Filter aktivieren</td>
						<td><input type="checkbox" id="cb-enable" name="enable" value="checked"<?php if ($data['terminalEnabled'] == true) echo ' checked="checked"'; ?> /><label for="cb-enable" class="checkbox only-checkbox">&nbsp;</label></td>
					</tr>
					<tr>
						<td>IP-Adressen zulassen</td>
						<td><textarea name="ip-addresses" style="height: 100px; width: 300px;"><?php echo $data['ipAddresses']; ?></textarea><br /><span class="small-info">Pro Zeile eine IP-Adresse<br />Beispiele: 192.168.1.0, 192.168.1.*, 192.168.1.0-192.168.1.125, 192.168.1/24</span></td>
					</tr>
					<tr>
						<td>Teste IP-Adresse</td>
						<td><input type="text" name="ip-address-check" value="<?php echo $data['ipAddressCheck']; ?>" /> <strong class="<?php echo ($data['ipAddressCheckStatus'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['ipAddressCheckStatus'] == true) ? 'Zugriff' : 'Zugriff verweigert'; ?></strong></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('Speichern'); ?>" />
			</div>
		</form>
	</div>
</div>