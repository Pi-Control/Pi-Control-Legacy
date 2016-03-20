<?php if (!defined('PICONTROL')) exit(); ?>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Fortschritt'); ?></span>
		</div>
		<div class="inner">
			<div class="progressbar"><div style="width: 80%;">&nbsp;</div></div>
		</div>
		<div class="inner text-center">
			<strong>80%</strong>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('4. Schritt'); ?></span>
		</div>
		<div class="inner">
			<strong class="green"><?php _e('Der Cron f&uuml;r das Pi Control wurde erfolgreich angelegt.'); ?></strong>
		</div>
		<div class="inner-end">
			<a href="?s=install_finish" class="button"><?php _e('N&auml;chster Schritt'); ?></a>
		</div>
	</div>
</div>