<?php if (!defined('PICONTROL')) exit(); ?>
<div class="sidebar order-2">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Version'); ?></span>
		</div>
		<div class="inner">
			<strong class="<?php echo (is_array($data['updateStatus'])) ? 'red' : 'green'; ?>"><?php  echo $data['configVersion']; ?></strong>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('E-Mail Benachrichtigung'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Wenn du zuk&uuml;nftig eine E-Mail bei einer neuen Version des Pi Control erhalten m&ouml;chtest, dann kannst du dich &uuml;ber den folgenden Button in die Liste eintragen.'); ?>
		</div>
		<div class="inner-end">
			<a href="<?php echo $data['configMailUrl']; ?>" class="button" target="_blank"><?php _e('E-Mail eintragen'); ?></a>
		</div>
	</div>
</div>
<div class="container-600 order-1">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Aktualisierung'); ?></span>
			<?php showGoBackIcon('?s=settings'); ?>
		</div>
		<div class="inner">
<?php if (is_array($data['updateStatus'])) { ?>
			<strong class="red"><?php _e('Dein Pi Control ist nicht mehr aktuell. Version %s ist verf&uuml;gbar!', $data['updateStatus']['version']); ?></strong><br /><br /><br />
			<span class="subtitle"><?php _e('Neuerungen in Version %s', $data['updateStatus']['version']); ?></span><br /><br />
			<?php echo $data['updateStatus']['changelog']; ?>
<?php } elseif ($data['updateStatus'] == 0) { ?>
			<strong class="green"><?php _e('Dein Pi Control ist auf dem neusten Stand.'); ?></strong>
<?php } elseif ($data['updateStatus'] == 1) { ?>
			<strong class="red"><?php _e('Bei der Verbindung zum Server ist ein Fehler aufgetreten. Der Server sendet eine fehlerhafte Antwort.'); ?></strong>
<?php } else { ?>
			<strong class="red"><?php _e('Bei der Verbindung zum Server ist ein unerwarteter Fehler aufgetreten. Fehlercode: %s', $data['updateStatus']); ?></strong>
<?php } ?>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Aktualisierung installieren'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Ist eine Aktualisierung verf&uuml;gbar, kannst du &uuml;ber folgenden Button dieses herunterladen und anschließend installieren.'); ?>
		</div>
		<div class="inner-end">
			<form action="?s=settings&amp;do=update" method="post">
				<input type="submit" name="update" value="<?php _e('Aktualisierung runterladen &amp; installieren'); ?>"<?php if (!is_array($data['updateStatus']) || $data['updateError'] == true) { echo ' disabled="disabled"'; } ?> />
			</form>
		</div>
	</div>
</div>