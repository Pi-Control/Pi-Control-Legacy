<?php if (!defined('PICONTROL')) exit(); ?>
<script type="text/javascript" src="public_html/js/shutdown.restart.js"></script>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Raspberry Pi wird neu gestartet'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Sobald dein Raspberry Pi wieder erreichbar ist, wirst du automatisch zur &Uuml;bersicht weitergeleitet.<br />Solltest du nicht weitergeleitet werden, kommst du hier <a href="%s">zur&uuml;ck zur &Uuml;bersicht.</a><br /><br />', $data['overviewUrl']); ?>
			<?php _e('Aktueller Status: <strong class="green">Online</strong>'); ?>
		</div>
	</div>
</div>