<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span>Netzwerkkonfiguration - Interface l&ouml;schen</span>
			<?php showGoBackIcon('?s=network_configuration'); ?>
		</div>
		<form action="?s=network_configuration&amp;delete=<?php echo urlencode($data['interfaceName']); ?>" method="post">
			<div class="inner">
				<?php _e('M&ouml;chtest du das Interface <strong>%s</strong> wirklich unwiderruflich l&ouml;schen?', $data['interfaceName']); ?>
			</div>
			<div class="inner-end">
				<input type="hidden" name="checksum" value="<?php echo $data['checksum']; ?>" />
				<input type="submit" name="submit" value="<?php _e('L&ouml;schen'); ?>" />
			</div>
		</form>
	</div>
</div>