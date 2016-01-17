<?php if (is_array($data['me']) && is_array($data['devices'])) { ?>
<div class="sidebar">
    <div class="box">
        <div class="inner-header">
            <span>Du / Deine Ger&auml;te</span>
        </div>
        <div class="inner">
            Verifiziert als <strong><?php echo $data['me']['name']; ?></strong>.
        </div>
        <div class="inner-table">
            <table class="table table-borderless">
                <tr>
                    <th>Ger&auml;tebezeichnung / Erstelldatum</th>
                </tr>
<?php foreach ($data['devices']['devices'] as $device) { ?>
                <tr>
                    <td><?php echo $device['nickname']; ?><br /><span class="small-info"><?php echo formatTime($device['created']); ?></span></td>
                </tr>
<?php } ?>
            </table>
        </div>
    </div>
</div>
<div class="container-600">
<?php } else { ?>
<div>
<?php } ?>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Benachrichtigung'); ?></span>
			<?php showGoBackIcon('?s=settings'); ?>
		</div>
		<div class="inner">
			Die Benachrichtigungen werden &uuml;ber Pushbullet realisiert. Daher ist ein Konto bei Pushbullet notwendig.
		</div>
		<form action="?s=settings&amp;do=notification" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Aktivieren</td>
						<td><input type="checkbox" name="activation" value="checked" id="cb-activation"<?php if ($data['activation'] === true) echo ' checked="checked"'; ?> /><label for="cb-activation" class="checkbox only-checkbox">&nbsp;</label></td>
					</tr>
					<tr>
						<td>Zugangstoken</td>
						<td><input type="text" name="token" maxlength="46" value="<?php echo $data['token']; ?>" style="width: 300px;" /></td>
					</tr>
<?php if (is_array($data['me']) && is_array($data['devices'])) { ?>
					<tr>
						<td>Testbenachrichtigung</td>
						<td><form action="?s=settings&amp;do=notification" method="post"><input type="submit" name="submit-test-notification" value="Jetzt senden" class="button-small" /></form></td>
					</tr>
<?php } ?>
				</table>
			</div>
			<div class="inner">
				<br /><br />
				<strong>Benachrichtige mich, wenn...</strong>
			</div>
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td style="width: 1%;"><input type="checkbox" name="event-pi-control-version" value="checked" id="cb-event-pi-control-version"<?php if ($data['pi-control-enabled'] === true) echo ' checked="checked"'; ?> /><label for="cb-event-pi-control-version" class="checkbox only-checkbox">&nbsp;</label></td>
						<td style="width: 35%;" colspan="2">eine neue Pi Control Version erscheint</td>
					</tr>
					<tr>
						<td style="width: 1%;"><input type="checkbox" name="event-cpu-temperature" value="checked" id="cb-event-cpu-temperature"<?php if ($data['cpu-temperature-enabled'] === true) echo ' checked="checked"'; ?> /><label for="cb-event-cpu-temperature" class="checkbox only-checkbox">&nbsp;</label></td>
						<td style="width: 35%;">die CPU-Temperatur einen Wert &uuml;berschreitet</td>
						<td><select name="event-cpu-temperature-maximum">
								<option style="background: #4CAF50;" value="40"<?php if ($data['cpu-temperature-maximum'] == 40) echo ' selected="selected"'; ?>>40 °C</option>
								<option style="background: #5ABC45;" value="45"<?php if ($data['cpu-temperature-maximum'] == 45) echo ' selected="selected"'; ?>>45 °C</option>
								<option style="background: #73CA3C;" value="50"<?php if ($data['cpu-temperature-maximum'] == 50) echo ' selected="selected"'; ?>>50 °C</option>
								<option style="background: #96D732;" value="55"<?php if ($data['cpu-temperature-maximum'] == 55) echo ' selected="selected"'; ?>>55 °C</option>
								<option style="background: #DCEB1E;" value="60"<?php if ($data['cpu-temperature-maximum'] == 60) echo ' selected="selected"'; ?>>60 °C</option>
								<option style="background: #FFC107;" value="65"<?php if ($data['cpu-temperature-maximum'] == 65) echo ' selected="selected"'; ?>>65 °C</option>
								<option style="background: #F89613;" value="70"<?php if ($data['cpu-temperature-maximum'] == 70) echo ' selected="selected"'; ?>>70 °C</option>
								<option style="background: #F2711F;" value="75"<?php if ($data['cpu-temperature-maximum'] == 75) echo ' selected="selected"'; ?>>75 °C</option>
								<option style="background: #EE5C27;" value="80"<?php if ($data['cpu-temperature-maximum'] == 80) echo ' selected="selected"'; ?>>80 °C</option>
								<option style="background: #E9492E;" value="85"<?php if ($data['cpu-temperature-maximum'] == 85) echo ' selected="selected"'; ?>>85 °C</option>
								<option style="background: #E53935;" value="90"<?php if ($data['cpu-temperature-maximum'] == 90) echo ' selected="selected"'; ?>>90 °C</option>
							</select></td>
					</tr>
					<tr>
						<td style="width: 1%;"><input type="checkbox" name="event-memory-used" value="checked" id="cb-event-memory-used"<?php if ($data['memory-used-enabled'] === true) echo ' checked="checked"'; ?> /><label for="cb-event-memory-used" class="checkbox only-checkbox">&nbsp;</label></td>
						<td style="width: 35%;">der Speicherverbrauch (Gesamtspeicher) einen Wert &uuml;berschreitet</td>
						<td><input type="text" name="event-memory-used-text" style="width: 30px !important;" maxlength="3" value="<?php echo $data['memory-used-limit']; ?>" /> %</td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="Speichern" />
			</div>
		</form>
	</div>
</div>