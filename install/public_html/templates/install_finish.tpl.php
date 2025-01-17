<?php if (!defined('PICONTROL')) exit(); ?>
<style>
@-webkit-keyframes move {
    0% {
       width: 80%;
    }
    30% {
       width: 95%;
    }
}
</style>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Fortschritt'); ?></span>
		</div>
		<div class="inner">
			<div class="progressbar"><div style="width: 95%; -webkit-animation: move 1.5s linear forwards;">&nbsp;</div></div>
		</div>
		<div class="inner text-center">
			<strong>95%</strong>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('5. Schritt'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Juhu, nun bist du fast mit der Installation des Pi Control fertig. Im letzten Schritt wird alles fertig konfiguriert und erstellt. Anschließend wirst du zum Pi Control weitergeleitet.<br /><br />Viel Spaß!'); ?>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Aktualisierungsbenachrichtigung'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Wenn du zuk&uuml;nftig eine E-Mail bei einer neuen Version des Pi Control erhalten m&ouml;chtest, dann kannst du dich <a href="%s" target="_blank">hier in die Liste eintragen</a>.', $data['configUpdateNotification']); ?>
		</div>
		<div class="inner-end">
			<form action="?s=install_finish" method="post">
				<input type="submit" name="submit" value="<?php _e('Abschließen'); ?>" />
			</form>
		</div>
	</div>
</div>