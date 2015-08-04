</div>
<!-- Footer -->
	<div id="footer">
		<div id="footer_inner"><a href="?s=settings"><?php _e('Einstellungen'); ?></a><a href="https://play.google.com/store/apps/details?id=de.willytech.picontrol" target="_blank" title="Öffne Play Store"><?php _e('App im Play Store'); ?></a><a href="http://willy-tech.de/kontakt/" target="_blank"><?php _e('Feedback'); ?></a><a href="{$help_link}" target="_blank"><?php _e('Hilfe'); ?></a><span id="servertime"><?php echo $data['servertime']; ?></span></div>
		<div id="footer_copyright"><p><?php _e('Version %s', $data['version']); ?></p><?php _e('Entwickelt von'); ?> <a href="http://willy-tech.de/" target="_blank">Willy Fritzsche</a>. 2013-2015.<p><?php _e('Das Raspberry Pi Logo steht unter der Lizenz von <a href="http://www.raspberrypi.org/" target="_blank">www.raspberrypi.org</a>'); ?></div>
	</div>
	<script type="text/javascript">var errorHandler = '<?php echo $data['errorHandler']; ?>';</script>
</body>
</html>