<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zu Plugins - Plugin l&ouml;schen'); ?></span>
			<?php showGoBackIcon('?s=settings&amp;do=plugins'); ?>
		</div>
		<form action="?s=settings&amp;do=plugins&amp;delete=<?php echo $data['plugin']['id']; ?>" method="post">
			<div class="inner">
				<?php _e('M&ouml;chtest du das Plugin <strong>%s</strong> wirklich unwiderruflich l&ouml;schen?', $data['plugin']['name']); ?>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('L&ouml;schen'); ?>" />
			</div>
		</form>
	</div>
</div>