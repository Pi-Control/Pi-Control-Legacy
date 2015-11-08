<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zur Statistik - Verlauf zur&uuml;cksetzen'); ?></span>
			<?php showGoBackIcon('?s=settings&amp;do=statistic'); ?>
		</div>
		<form action="?s=settings&amp;do=statistic&amp;reset=<?php echo $data['log']; ?>&amp;confirm" method="post">
			<div class="inner">
				<?php _e('M&ouml;chtest du den Verlauf von %s wirklich zur&uuml;cksetzen?', '<strong>'.$data['label'].'</strong>'); ?>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('Zur&uuml;cksetzen'); ?>" />
			</div>
		</form>
	</div>
</div>