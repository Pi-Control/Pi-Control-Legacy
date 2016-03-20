<?php if (!defined('PICONTROL')) exit(); ?>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Fortschritt'); ?></span>
		</div>
		<div class="inner">
			<div class="progressbar"><div style="width: 60%;">&nbsp;</div></div>
		</div>
		<div class="inner text-center">
			<strong>60%</strong>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('3. Schritt'); ?></span>
		</div>
		<div class="inner">
			<strong class="green"><?php _e('Der Benutzer f&uuml;r das Pi Control wurde erfolgreich angelegt.'); ?></strong>
		</div>
		<div class="inner-end">
			<a href="?s=install_cron" class="button"><?php _e('N&auml;chster Schritt'); ?></a>
		</div>
	</div>
</div>