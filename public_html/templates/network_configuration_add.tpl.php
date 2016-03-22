<?php if (!defined('PICONTROL')) exit(); ?>
<script type="text/javascript" src="public_html/js/network_configuration.method_select.js"></script>
<div>
	<div class="box">
		<div class="inner-header">
			<span>Netzwerkkonfiguration - Interface hinzuf&uuml;gen</span>
			<?php showGoBackIcon('?s=network_configuration'); ?>
		</div>
		<form action="?s=network_configuration&amp;add" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Interface</td>
						<td><input type="text" name="interface" value="<?php echo $data['interfaceName']; ?>" /></td>
					</tr>
					<tr>
						<td>Protokoll</td>
						<td><select name="protocol">
								<option value="inet"<?php if ($data['interfaceProtocol'] == 'inet') echo ' selected="selected"'; ?>>IPv4</option>
								<option value="inet6"<?php if ($data['interfaceProtocol'] == 'inet6') echo ' selected="selected"'; ?>>IPv6</option>
								<option value="ipx"<?php if ($data['interfaceProtocol'] == 'ipx') echo ' selected="selected"'; ?>>IPX/SPX</option>
							</select>
							<span class="small-info">Weitere Einstellungen für IPX/SPX bitte selber vornehmen</span>
						</td>
					</tr>
					<tr>
						<td>Methode</td>
						<td><select name="method">
								<option value="dhcp"<?php if ($data['interfaceMethod'] == 'dhcp') echo ' selected="selected"'; ?>>DHCP</option>
								<option value="static"<?php if ($data['interfaceMethod'] == 'static') echo ' selected="selected"'; ?>>Statisch</option>
								<option value="manual"<?php if ($data['interfaceMethod'] == 'manual') echo ' selected="selected"'; ?>>Manuell</option>
							</select>
						</td>
					</tr>
					<tr class="hidden-method<?php if ($data['interfaceMethod'] != 'static') echo ' display-none'; ?>">
						<td style="text-align: right;">Adresse</td>
						<td><input type="text" name="address" maxlength="39" value="<?php echo $data['interfaceAddress'] ?>" /></td>
					</tr>
					<tr class="hidden-method<?php if ($data['interfaceMethod'] != 'static') echo ' display-none'; ?>">
						<td style="text-align: right;">Netzmaske</td>
						<td><input type="text" name="netmask" maxlength="39" value="<?php echo $data['interfaceNetmask']; ?>" /></td>
					</tr>
					<tr class="hidden-method<?php if ($data['interfaceMethod'] != 'static') echo ' display-none'; ?>">
						<td style="text-align: right;">Gateway</td>
						<td><input type="text" name="gateway" maxlength="39" value="<?php echo $data['interfaceGateway']; ?>" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="hidden" name="checksum" value="<?php echo $data['checksum']; ?>" />
				<input type="submit" name="submit" value="Speichern" />
			</div>
		</form>
	</div>
</div>