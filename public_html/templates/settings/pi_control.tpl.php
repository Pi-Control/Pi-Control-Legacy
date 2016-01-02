<script type="text/javascript" src="public_html/js/settings.pi_control.theme.js"></script>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zum Pi Control'); ?></span>
			<?php showGoBackIcon('?s=settings'); ?>
		</div>
		<form action="?s=settings&amp;do=pi_control" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td>Theme-Farbe</td>
						<td><input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'red') echo ' checked="checked"'; ?> value="red" id="rb-red" /><label for="rb-red" class="radio only-radio settings-pi-control-theme-color-red">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'pink') echo ' checked="checked"'; ?> value="pink" id="rb-pink" /><label for="rb-pink" class="radio only-radio settings-pi-control-theme-color-pink">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'purple') echo ' checked="checked"'; ?> value="purple" id="rb-purple" /><label for="rb-purple" class="radio only-radio settings-pi-control-theme-color-purple">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'deepPurple') echo ' checked="checked"'; ?> value="deepPurple" id="rb-deepPurple" /><label for="rb-deepPurple" class="radio only-radio settings-pi-control-theme-color-deepPurple">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'indigo') echo ' checked="checked"'; ?> value="indigo" id="rb-indigo" /><label for="rb-indigo" class="radio only-radio settings-pi-control-theme-color-indigo">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'blue') echo ' checked="checked"'; ?> value="blue" id="rb-blue" /><label for="rb-blue" class="radio only-radio settings-pi-control-theme-color-blue">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'lightBlue') echo ' checked="checked"'; ?> value="lightBlue" id="rb-lightBlue" /><label for="rb-lightBlue" class="radio only-radio settings-pi-control-theme-color-lightBlue">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'cyan') echo ' checked="checked"'; ?> value="cyan" id="rb-cyan" /><label for="rb-cyan" class="radio only-radio settings-pi-control-theme-color-cyan">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'teal') echo ' checked="checked"'; ?> value="teal" id="rb-teal" /><label for="rb-teal" class="radio only-radio settings-pi-control-theme-color-teal">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'green') echo ' checked="checked"'; ?> value="green" id="rb-green" /><label for="rb-green" class="radio only-radio settings-pi-control-theme-color-green">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'lightGreen') echo ' checked="checked"'; ?> value="lightGreen" id="rb-lightGreen" /><label for="rb-lightGreen" class="radio only-radio settings-pi-control-theme-color-lightGreen">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'lime') echo ' checked="checked"'; ?> value="lime" id="rb-lime" /><label for="rb-lime" class="radio only-radio settings-pi-control-theme-color-lime">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'yellow') echo ' checked="checked"'; ?> value="yellow" id="rb-yellow" /><label for="rb-yellow" class="radio only-radio settings-pi-control-theme-color-yellow">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'amber') echo ' checked="checked"'; ?> value="amber" id="rb-amber" /><label for="rb-amber" class="radio only-radio settings-pi-control-theme-color-amber">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'orange') echo ' checked="checked"'; ?> value="orange" id="rb-orange" /><label for="rb-orange" class="radio only-radio settings-pi-control-theme-color-orange">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'deepOrange') echo ' checked="checked"'; ?> value="deepOrange" id="rb-deepOrange" /><label for="rb-deepOrange" class="radio only-radio settings-pi-control-theme-color-deepOrange">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'brown') echo ' checked="checked"'; ?> value="brown" id="rb-brown" /><label for="rb-brown" class="radio only-radio settings-pi-control-theme-color-brown">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'grey') echo ' checked="checked"'; ?> value="grey" id="rb-grey" /><label for="rb-grey" class="radio only-radio settings-pi-control-theme-color-grey">&nbsp;</label>
							<input type="radio" name="theme-color"<?php if ($data['main-theme-color'] == 'blueGrey') echo ' checked="checked"'; ?> value="blueGrey" id="rb-blueGrey" /><label for="rb-blueGrey" class="radio only-radio settings-pi-control-theme-color-blueGrey">&nbsp;</label></td>
					</tr>
					<tr>
						<td>Bezeichnung</td>
						<td><input type="text" name="pi-control-label"<?php echo ' value="'.$data['main-pi-control-label'].'"'; ?> maxlength="32" /> <span class="small-info">Bennene dein Pi Control, um es in Benachrichtigungen besser identifizieren zu können.</span></td>
					</tr>
					<tr>
						<td>Externer Zugriff</td>
						<td><input type="checkbox" id="cb-external-access" name="external-access" value="checked"<?php if ($data['main-external-access'] == 'true') echo ' checked="checked"'; ?> /><label for="cb-external-access" class="checkbox only-checkbox">&nbsp;</label> <span class="small-info">Auch außerhalb des lokalen Netzwerk erreichbar? Ggf. an Port-Weiterleitung denken.</span></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit-main" value="<?php _e('Speichern'); ?>" />
			</div>
		</form>
	</div>
	<div class="box">
		<div class="inner-header">
			<span>Temperatur&uuml;berwachung</span>
		</div>
		<div class="inner">
			Aktiviere diese Option, um von deinem Raspberry Pi, beim &Uuml;berschreiten einer bestimmten Temperatur benachrichtigt zu werden. F&uuml;r diesen Fall lassen sich auch spezifische Verhalten festlegen.
		</div>
		<form action="?s=settings&amp;do=pi_control" method="post">
			<div class="inner-table overflow-auto">
				<table class="table table-borderless table-form">
					<tr>
						<td>Aktivieren</td>
						<td colspan="2"><input type="checkbox" name="temperature-activation" id="cb-temperature" value="checked"<?php if ($data['temperature-activation'] === true) echo ' checked="checked"'; ?> /><label for="cb-temperature" class="checkbox only-checkbox">&nbsp;</label></td>
					</tr>
					<tr>
						<td>H&ouml;chsttemperatur</td>
						<td colspan="2"><select name="temperature-maximum">
								<option style="background: #4CAF50;" value="40"<?php if ($data['temperature-maximum'] == 40) echo ' selected="selected"'; ?>>40 °C</option>
								<option style="background: #5ABC45;" value="45"<?php if ($data['temperature-maximum'] == 45) echo ' selected="selected"'; ?>>45 °C</option>
								<option style="background: #73CA3C;" value="50"<?php if ($data['temperature-maximum'] == 50) echo ' selected="selected"'; ?>>50 °C</option>
								<option style="background: #96D732;" value="55"<?php if ($data['temperature-maximum'] == 55) echo ' selected="selected"'; ?>>55 °C</option>
								<option style="background: #DCEB1E;" value="60"<?php if ($data['temperature-maximum'] == 60) echo ' selected="selected"'; ?>>60 °C</option>
								<option style="background: #FFC107;" value="65"<?php if ($data['temperature-maximum'] == 65) echo ' selected="selected"'; ?>>65 °C</option>
								<option style="background: #F89613;" value="70"<?php if ($data['temperature-maximum'] == 70) echo ' selected="selected"'; ?>>70 °C</option>
								<option style="background: #F2711F;" value="75"<?php if ($data['temperature-maximum'] == 75) echo ' selected="selected"'; ?>>75 °C</option>
								<option style="background: #EE5C27;" value="80"<?php if ($data['temperature-maximum'] == 80) echo ' selected="selected"'; ?>>80 °C</option>
								<option style="background: #E9492E;" value="85"<?php if ($data['temperature-maximum'] == 85) echo ' selected="selected"'; ?>>85 °C</option>
								<option style="background: #E53935;" value="90"<?php if ($data['temperature-maximum'] == 90) echo ' selected="selected"'; ?>>90 °C</option>
							</select></td>
					</tr>
					<tr>
						<td>Aktion</td>
						<td style="width: 130px;"><input type="checkbox" name="temperature-action-email" id="cb-temperature-email" value="checked"<?php if ($data['temperature-action-email'] == true) echo ' checked="checked"'; ?> /><label for="cb-temperature-email" class="checkbox">E-Mail senden</label></td>
						<td><input type="text" name="temperature-action-email-text"<?php echo ' value="'.$data['temperature-action-email-text'].'"'; ?> />
							<?php if ($data['temperature-action-email-text'] != '') { echo ($data['temperature-action-email-status'] == 0) ? '<form action="?s=settings&amp;do=pi_control" method="post"><input type="submit" name="submit-temperature-confirmation" value="Best&auml;tigen" /></form> <a href="?s=settings&amp;do=pi_control&amp;mail_check" class="button">&Uuml;berpr&uuml;fen</a>' : '<strong class="green">Bestätigt</strong>'; } ?></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="checkbox" name="temperature-action-shell" id="cb-temperature-shell" value="checked"<?php if ($data['temperature-action-shell'] == true) echo ' checked="checked"'; ?> /><label for="cb-temperature-shell" class="checkbox">Shell Befehl</label></td>
						<td><input type="text" name="temperature-action-shell-text"<?php echo ' value="'.$data['temperature-action-shell-text'].'"'; ?> /></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2"><input type="checkbox" name="temperature-action-shutdown" id="cb-temperature-shutdown" value="checked"<?php if ($data['temperature-action-shutdown'] == 'true') echo ' checked="checked"'; ?> /><label for="cb-temperature-shutdown" class="checkbox">Herunterfahren</label></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit-temperature" value="<?php _e('Speichern'); ?>" />
			</div>
		</form>
	</div>
	<div class="box">
		<div class="inner-header">
			<span>Pi Control entfernen</span>
		</div>
		<div class="inner">
			Wenn dir das Pi Control nicht gef&auml;llt und du es wieder entfernen m&ouml;chtest, schreib mir unten unter "Feedback" doch bitte den Grund. Somit kann ich besser auf m&ouml;gliche Probleme eingehen und Pi Control st&auml;tig verbessern. Eine genaue Anleitung zum Entfernen des Pi Control findest du mit einem Klick auf den folgenden Button.
		</div>
		<div class="inner-end">
			<a href="#" class="button">Anleitung zum Entfernen</a>
		</div>
	</div>
</div>