<?php if (!defined('PICONTROL')) exit(); ?>
<div class="sidebar order-2">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Version'); ?></span>
		</div>
		<div class="inner">
			<strong class="<?php echo ($data['updateStatus'] instanceof Update) ? 'red' : 'green'; ?>"><?php  echo $data['configVersion']; ?></strong>
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
<?php if ($data['updateStatus'] instanceof Update) { ?>
			<strong class="red"><?php _e('Dein Pi Control ist nicht mehr aktuell. Version %s ist verf&uuml;gbar!', $data['updateStatus']->getName()); ?></strong><br /><br /><br />
			<span class="subtitle"><?php _e('Neuerungen in Version %s', $data['updateStatus']->getName()); ?></span><br /><br />
			<?php echo $data['updateStatus']->getChangelog(); ?>
<?php } elseif ($data['updateStatus'] === false) { ?>
			<strong class="green"><?php _e('Dein Pi Control ist auf dem neusten Stand.'); ?></strong>
<?php } else { ?>
			<strong class="red"><?php _e('Bei der Verbindung zum Server ist ein unerwarteter Fehler aufgetreten. Fehlercode: %d', $data['updateStatus']); ?></strong>
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
				<input type="submit" name="update" value="<?php _e('Aktualisierung runterladen &amp; installieren'); ?>"<?php if (!$data['updateStatus'] instanceof Update || $data['updateError'] == true) { echo ' disabled="disabled"'; } ?> />
			</form>
		</div>
	</div>
</div>
<div class="clear-both"></div>
<div class="order-3">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Pi Control Beta'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Mit der Teilnahme an Pi Control Beta erh&auml;ltst du, bereits vor Ver&ouml;ffentlichung der finalen Version von Pi Control, einen ersten Einblick in kommende Funktionen und Neuerungen. Durch die Teilnahme bekommst du, neben der Aktualisierungen f&uuml;r die Beta, auch die regul&auml;ren und stabilen Aktualisierungen angeboten.<br />Du kannst jederzeit von Pi Control Beta zur&uuml;cktreten, ein Zur&uuml;ckstufen auf eine &auml;ltere/regul&auml;re Version ist allerdings nicht m&ouml;glich. Anschließend erh&auml;ltst du wieder wie gewohnt die stabilen Aktualisierungen.'); ?>
			<br /><br />
			<strong class="red"><?php _e('BEACHTE: W&auml;hrend der Beta kann Pi Control unerwartet Auffallen, sei es durch Fehler oder Datenverlust (nur Pi Control). Sende mir deshalb unbedingt bei Fehlern dein Feedback (siehe Unten).'); ?></strong>
		</div>
		<div class="inner-end">
			<form action="?s=settings&amp;do=update" method="post">
				<input type="submit" name="beta" value="<?php _e($data['updateStage'] == 'release' ? 'An Pi Control Beta teilnehmen' : 'Von Pi Control Beta zur&uuml;cktreten'); ?>" />
			</form>
		</div>
	</div>
</div>