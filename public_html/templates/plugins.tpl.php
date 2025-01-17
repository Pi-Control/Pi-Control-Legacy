<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Plugins'); ?></span>
			<?php showSettingsIcon('?s=settings&amp;do=plugins'); ?>
		</div>
<?php $i = 0; foreach ($data['plugins'] as $plugin) { $i++; ?>
		<div class="inner-table plugins-table-list<?php echo ($i != count($data['plugins'])) ? ' padding-0' : ''; ?>">
			<a href="?s=plugins&amp;id=<?php echo $plugin['id']; ?>">
				<table class="table table-borderless<?php echo ($i%2 != 0) ? ' table-reverse' : ''; ?>">
					<tr>
						<td><strong><?php _e($plugin['name']); ?></strong><span><?php _e('Version %s', $plugin['version']['name']); ?></span></td>
						<td><?php _e($plugin['description']); ?></td>
					</tr>
				</table>
			</a>
		</div>
<?php } if (empty($data['plugins'])) { ?>
		<div class="inner-info">
			<div><?php _e('Es sind momentan noch keine Plugins installiert.'); ?></div>
		</div>
<?php } ?>
		<div class="inner-end">
			<a href="?s=discover_plugins"><button><?php _e('Plugins entdecken'); ?></button></a>
		</div>
	</div>
</div>