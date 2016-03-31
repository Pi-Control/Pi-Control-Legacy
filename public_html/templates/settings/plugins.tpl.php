<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zu Plugins'); ?></span>
		</div>
<?php $i = 0; foreach ($data['plugins'] as $plugin) { $i++; ?>
		<div class="inner-table settings-plugins-table-list<?php echo ($i != count($data['plugins'])) ? ' padding-0' : ''; ?>">
			<table class="table table-borderless<?php echo ($i%2 != 0) ? ' table-reverse' : ''; ?>">
				<tr>
					<td><strong><?php _e($plugin['name']); ?></strong><span><?php _e('Version %s', $plugin['version']['name']); ?></span></td>
					<td><a href="?s=plugins&amp;id=<?php echo $plugin['id']; ?>&amp;settings" class="text-decoration-none"<?php if ($plugin['settings'] == false) echo ' style="visibility: hidden"'; ?>><span class="button button-small"><?php _e('Einstellungen'); ?></span></a>
						<a href="?s=settings&amp;do=plugins&amp;status=<?php echo $plugin['id']; ?>" class="text-decoration-none"><span class="button button-small"><?php echo ($plugin['disabled'] == true) ? _t('Aktivieren') : _t('Deaktivieren'); ?></span></a>
						<a href="?s=settings&amp;do=plugins&amp;delete=<?php echo $plugin['id']; ?>" class="text-decoration-none"><span class="button button-small"><?php _e('L&ouml;schen'); ?></span></a></td>
					<td><?php _e($plugin['description']); ?></td>
				</tr>
			</table>
		</div>
<?php } ?>
	</div>
</div>