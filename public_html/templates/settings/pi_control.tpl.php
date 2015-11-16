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
						<td><input type="radio" name="theme-color" value="red" id="rb-red" /><label for="rb-red" class="radio only-radio settings-pi-control-theme-color-red">&nbsp;</label>
							<input type="radio" name="theme-color" value="pink" id="rb-pink" /><label for="rb-pink" class="radio only-radio settings-pi-control-theme-color-pink">&nbsp;</label>
							<input type="radio" name="theme-color" value="purple" id="rb-purple" /><label for="rb-purple" class="radio only-radio settings-pi-control-theme-color-purple">&nbsp;</label>
							<input type="radio" name="theme-color" value="deepPurple" id="rb-deepPurple" /><label for="rb-deepPurple" class="radio only-radio settings-pi-control-theme-color-deepPurple">&nbsp;</label>
							<input type="radio" name="theme-color" value="indigo" id="rb-indigo" /><label for="rb-indigo" class="radio only-radio settings-pi-control-theme-color-indigo">&nbsp;</label>
							<input type="radio" name="theme-color" value="blue" id="rb-blue" /><label for="rb-blue" class="radio only-radio settings-pi-control-theme-color-blue">&nbsp;</label>
							<input type="radio" name="theme-color" value="lightBlue" id="rb-lightBlue" /><label for="rb-lightBlue" class="radio only-radio settings-pi-control-theme-color-lightBlue">&nbsp;</label>
							<input type="radio" name="theme-color" value="cyan" id="rb-cyan" /><label for="rb-cyan" class="radio only-radio settings-pi-control-theme-color-cyan">&nbsp;</label>
							<input type="radio" name="theme-color" value="teal" id="rb-teal" /><label for="rb-teal" class="radio only-radio settings-pi-control-theme-color-teal">&nbsp;</label>
							<input type="radio" name="theme-color" value="green" id="rb-green" /><label for="rb-green" class="radio only-radio settings-pi-control-theme-color-green">&nbsp;</label>
							<input type="radio" name="theme-color" value="lightGreen" id="rb-lightGreen" /><label for="rb-lightGreen" class="radio only-radio settings-pi-control-theme-color-lightGreen">&nbsp;</label>
							<input type="radio" name="theme-color" value="lime" id="rb-lime" /><label for="rb-lime" class="radio only-radio settings-pi-control-theme-color-lime">&nbsp;</label>
							<input type="radio" name="theme-color" value="yellow" id="rb-yellow" /><label for="rb-yellow" class="radio only-radio settings-pi-control-theme-color-yellow">&nbsp;</label>
							<input type="radio" name="theme-color" value="amber" id="rb-amber" /><label for="rb-amber" class="radio only-radio settings-pi-control-theme-color-amber">&nbsp;</label>
							<input type="radio" name="theme-color" value="orange" id="rb-orange" /><label for="rb-orange" class="radio only-radio settings-pi-control-theme-color-orange">&nbsp;</label>
							<input type="radio" name="theme-color" value="deepOrange" id="rb-deepOrange" /><label for="rb-deepOrange" class="radio only-radio settings-pi-control-theme-color-deepOrange">&nbsp;</label>
							<input type="radio" name="theme-color" value="brown" id="rb-brown" /><label for="rb-brown" class="radio only-radio settings-pi-control-theme-color-brown">&nbsp;</label>
							<input type="radio" name="theme-color" value="grey" id="rb-grey" /><label for="rb-grey" class="radio only-radio settings-pi-control-theme-color-grey">&nbsp;</label>
							<input type="radio" name="theme-color" value="blueGrey" id="rb-blueGrey" /><label for="rb-blueGrey" class="radio only-radio settings-pi-control-theme-color-blueGrey">&nbsp;</label></td>
					</tr>
					<tr>
						<td>Externer Zugriff</td>
						<td><input type="checkbox" id="cb-external-access" name="" value="" /><label for="cb-external-access" class="checkbox only-checkbox">&nbsp;</label> <span class="small-info">Auch außerhalb des lokalen Netzwerk erreichbar? Ggf. an Port-Weiterleitung denken.</span></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('Speichern'); ?>" />
			</div>
		</form>
	</div>
</div>