<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zur &Uuml;bersicht'); ?></span>
			<?php showGoBackIcon('?s=overview'); ?>
		</div>
		<form action="?s=settings&amp;do=overview" method="post">
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td><?php _e('Intervall &Uuml;bersicht-Aktualisierung'); ?></td>
						<td><input type="text" name="overview-interval" maxlength="4" style="width: 50px;" value="<?php echo $data['main-overview-interval']; ?>" /> <?php _e('Sekunden'); ?></td>
					</tr>
					<tr>
						<td><?php _e('Zeige "Angeschlossene Ger&auml;te"'); ?></td>
						<td><input type="checkbox" id="cb-show-devices" name="show-devices" value="checked"<?php if ($data['main-show-devices'] == 'true') echo ' checked="checked"'; ?> /><label for="cb-show-devices" class="checkbox only-checkbox">&nbsp;</label> <span class="small-info"><?php _e('Das aktivieren verursacht eine l&auml;ngere Ladezeit der &Uuml;bersicht.'); ?></span></td>
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
			<span><?php _e('Wetter'); ?></span>
		</div>
		<form action="?s=settings&amp;do=overview" method="post">
			<div class="inner-table overflow-auto">
				<table class="table table-borderless table-form">
					<tr>
						<td><?php _e('Aktivieren'); ?></td>
						<td colspan="2"><input type="checkbox" name="weather-activation" id="cb-weather" value="checked"<?php if ($data['weather-activation'] === true) echo ' checked="checked"'; ?> /><label for="cb-weather" class="checkbox only-checkbox">&nbsp;</label></td>
					</tr>
					<tr>
						<td><?php _e('Dienst'); ?></td>
						<td><select name="weather-service" onchange="if (this.selectedIndex == 0) jQuery('input[name=weather-service-token]').show(); else jQuery('input[name=weather-service-token]').hide();">
								<option value="openweathermap"<?php if ($data['weather-service'] == 'openweathermap') echo ' selected="selected"'; ?>>OpenWeatherMap</option>
								<option value="yahoo"<?php if ($data['weather-service'] == 'yahoo') echo ' selected="selected"'; ?>>Yahoo Weather</option>
							</select></td>
						<td><input type="text" name="weather-service-token" value="<?php echo $data['weather-service-token']; ?>" placeholder="API-Key" maxlength="32"<?php if ($data['weather-service'] == 'yahoo') echo ' style="display: none;"'; ?> /></td>
					</tr>
					<tr>
						<td><?php _e('Standort'); ?></td>
						<td style="width: 100px; padding-left: 32px;"><?php _e('Land'); ?></td>
						<td><select name="weather-location-country">
								<option value="germany"<?php if ($data['weather-country'] == 'germany') echo ' selected="selected"'; ?>><?php _e('Deutschland'); ?></option>
								<option value="austria"<?php if ($data['weather-country'] == 'austria') echo ' selected="selected"'; ?>><?php _e('&Ouml;sterreich'); ?></option>
								<option value="swiss"<?php if ($data['weather-country'] == 'swiss') echo ' selected="selected"'; ?>><?php _e('Schweiz'); ?></option>
								<option value="uk"<?php if ($data['weather-country'] == 'uk') echo ' selected="selected"'; ?>><?php _e('Großbritannien'); ?></option>
							</select> <?php if (is_array($data['weather-info'])) echo $data['weather-info']['city'].', '.$data['weather-info']['country']; elseif ($data['weather-info'] == 1) echo '<strong class="red">'._t('Server nicht erreichbar!').'</strong>'; ?></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="radio" name="weather-location-type" id="cb-weather-location-postcode" value="postcode"<?php if ($data['weather-type'] == 'postcode') echo ' checked="checked"'; ?> /><label for="cb-weather-location-postcode" class="radio"><?php _e('Postleitzahl'); ?></label></td>
						<td><input type="text" name="weather-location-postcode-text" value="<?php echo $data['weather-postcode']; ?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="radio" name="weather-location-type" id="cb-weather-location-city" value="city"<?php if ($data['weather-type'] == 'city') echo ' checked="checked"'; ?> /><label for="cb-weather-location-city" class="radio"><?php _e('Stadt'); ?></label></td>
						<td><input type="text" name="weather-location-city-text" value="<?php echo $data['weather-city']; ?>" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit-weather" value="<?php _e('Speichern'); ?>" />
			</div>
		</form>
	</div>
</div>