<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Benachrichtigung'); ?></span>
			<?php showGoBackIcon('?s=settings'); ?>
		</div>
		<div class="inner">
			Die Benachrichtigungen werden über Pushbullet realisiert. Daher ist ein Konto bei Pushbullet notwendig.
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form">
				<tr>
					<td>Aktivieren</td>
					<td><input type="checkbox" name="activation" value="checked" id="cb-activation" /><label for="cb-activation" class="checkbox only-checkbox">&nbsp;</label></td>
				</tr>
				<tr>
					<td>Zugangstoken</td>
					<td><input type="text" name="token" /></td>
				</tr>
			</table>
		</div>
		<div class="inner">
			<br /><br />
			<strong>Benachrichtige mich, wenn...</strong>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form">
				<tr>
					<td style="padding-left: 30px; width: 40%;">eine neue Pi Control Version erscheint</td>
					<td><input type="checkbox" name="event-pi-control-version" value="checked" id="cb-event-pi-control-version" /><label for="cb-event-pi-control-version" class="checkbox only-checkbox">&nbsp;</label></td>
				</tr>
				<tr>
					<td style="padding-left: 30px;">die CPU-Temperatur einen Wert überschreitet</td>
					<td><input type="checkbox" name="event-cpu-temperature" value="checked" id="cb-event-cpu-temperature" /><label for="cb-event-cpu-temperature" class="checkbox only-checkbox">&nbsp;</label>
						<select name="event-cpu-temperature-maximum">
							<option style="background: #4CAF50;" value="40">40 °C</option>
							<option style="background: #5ABC45;" value="45">45 °C</option>
							<option style="background: #73CA3C;" value="50">50 °C</option>
							<option style="background: #96D732;" value="55">55 °C</option>
							<option style="background: #DCEB1E;" value="60">60 °C</option>
							<option style="background: #FFC107;" value="65" selected="selected">65 °C</option>
							<option style="background: #F89613;" value="70">70 °C</option>
							<option style="background: #F2711F;" value="75">75 °C</option>
							<option style="background: #EE5C27;" value="80">80 °C</option>
							<option style="background: #E9492E;" value="85">85 °C</option>
							<option style="background: #E53935;" value="90">90 °C</option>
						</select></td>
				</tr>
				<tr>
					<td style="padding-left: 30px;">die CPU-Temperatur einen Wert überschreitet</td>
					<td><input type="checkbox" name="event-memory-used" value="checked" id="cb-event-memory-used" /><label for="cb-event-memory-used" class="checkbox only-checkbox">&nbsp;</label>
						<input type="text" name="event-memory-used-text" value="80" style="width: 30px;" maxlength="2" /></td>
				</tr>
			</table>
		</div>
		<div class="inner-end">
			<input type="submit" name="submit" value="Speichern" />
		</div>
	</div>
</div>