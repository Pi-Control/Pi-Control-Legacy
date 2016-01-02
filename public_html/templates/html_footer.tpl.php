</div>
<!-- Footer -->
	<div id="footer">
		<div id="footer-inner">
			<table id="footer-table">
				<tr>
					<th><?php _e('PI CONTROL'); ?></th>
					<th><?php _e('&Uuml;BER MICH'); ?></th>
					<th><?php _e('VERSION'); ?></th>
				</tr>
				<tr>
					<td rowspan="2"><a href="?s=settings"><?php _e('Einstellungen'); ?></a><br />
						<a href="http://willy-tech.de/kontakt/" target="_blank"><?php _e('Feedback'); ?></a><br />
						<a href="<?php echo $data['helpLink']; ?>" target="_blank"><?php _e('Hilfe'); ?></a><br />
						<a href="https://play.google.com/store/apps/details?id=de.willytech.picontrol" target="_blank" title="<?php _e('Öffne Play Store'); ?>"><?php _e('App im Play Store'); ?></a></td>
					<td rowspan="2"><a href="http://willy-tech.de/" target="_blank"><?php _e('Mein Blog'); ?></a><br />
						<a href="https://twitter.com/Willys_TechBlog" target="_blank"><?php _e('Twitter'); ?></a><br />
						<a href="https://github.com/willfri" target="_blank"><?php _e('GitHub'); ?></a><br />
						<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=7PKQV4RWDS7E4" target="_blank"><?php _e('Spenden'); ?></a></td>
					<td>2.0.0</td>
				</tr>
				<tr>
					<td><strong><?php _e('LIZENZ'); ?></strong><span><?php _e('Raspberry Pi ist ein Markenzeichen<br />der %s.', '<a href="http://www.raspberrypi.org/" target="_blank">Raspberry Pi Foundation</a>'); ?></span></td>
				</tr>
			</table>
			<div id="footer-copyright"><?php _e('Mit %s entwickelt von %s.', '<span style="color: #F44336;">&#10084;</span>', '<a href="http://willy-tech.de/" target="_blank">Willy Fritzsche</a>'); ?> 2013-2016</div>
		</div>
	</div>
	<script type="text/javascript">var errorHandler = '<?php echo $data['errorHandler']; ?>';</script>
</body>
</html>