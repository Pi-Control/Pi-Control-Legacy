<?php if (!defined('PICONTROL')) exit(); ?>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Statistik'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th><?php _e('Plugins'); ?></th>
					<th><?php _e('Anzahl'); ?></th>
				</tr>
				<tr>
					<td><?php _e('Insgesamt'); ?></td>
					<td><?php echo count($data['onlinePlugins']); ?></td>
				</tr>
				<tr>
					<td><?php _e('Installiert'); ?></td>
					<td><?php echo count($data['plugins']); ?></td>
				</tr>
				<tr>
					<td><?php _e('Update'); ?></td>
					<td><?php echo count($data['availableUpdates']); ?></td>
				</tr>
				<tr>
					<td><?php _e('Deaktiviert'); ?></td>
					<td><?php echo $data['disabledPluginsCount']; ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Plugins entdecken'); ?></span>
			<?php showGoBackIcon('?s=plugins'); ?>
		</div>
<?php $i = 0; foreach ($data['onlinePlugins'] as $plugin) { $i++; ?>
		<div class="inner-table discover_plugins-table-list<?php echo ($i != count($data['onlinePlugins'])) ? ' padding-0' : ''; ?>">
			<a href="?s=discover_plugins&amp;id=<?php echo $plugin['id']; ?>">
				<table class="table table-borderless<?php echo ($i%2 != 0) ? ' table-reverse' : ''; ?>">
					<tr>
						<td><strong><?php echo $plugin['name']; ?></strong><span><?php _e('Version %s', $plugin['versions'][$plugin['latestVersion']]['name']); ?></span><?php if (isset($data['plugins'][$plugin['id']])) { ?><strong class="<?php if (isset($data['availableUpdates'][$plugin['id']])) echo 'orange'; else echo ($data['plugins'][$plugin['id']]['disabled'] == true) ? 'red' : 'green'; ?>"><?php if (isset($data['availableUpdates'][$plugin['id']])) echo _t('Update'); else  echo ($data['plugins'][$plugin['id']]['disabled'] == true) ? _t('Deaktiviert') : _t('Installiert'); ?></strong><?php } ?></td>
						<td><?php echo $plugin['description']; ?></td>
					</tr>
				</table>
			</a>
		</div>
<?php } if (empty($data['onlinePlugins'])) { ?>
		<div class="inner-info">
			<div><?php _e('Es konnten keine verf&uuml;gbaren Plugins gefunden werden.'); ?></div>
		</div>
<?php } ?>
	</div>
</div>