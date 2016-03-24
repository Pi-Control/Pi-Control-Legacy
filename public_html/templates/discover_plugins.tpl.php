<?php if (!defined('PICONTROL')) exit(); ?>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span>Statistik</span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th>Plugins</th>
					<th>Anzahl</th>
				</tr>
				<tr>
					<td>Insgesamt</td>
					<td><?php echo count($data['onlinePlugins']); ?></td>
				</tr>
				<tr>
					<td>Installiert</td>
					<td><?php echo count($data['plugins']); ?></td>
				</tr>
				<tr>
					<td>Update</td>
					<td><?php echo count($data['availableUpdates']); ?></td>
				</tr>
				<tr>
					<td>Deaktiviert</td>
					<td><?php echo $data['disabledPluginsCount']; ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span>Plugins entdecken</span>
			<?php showGoBackIcon('?s=plugins'); ?>
		</div>
<?php $i = 0; foreach ($data['onlinePlugins'] as $plugin) { $i++; ?>
		<div class="inner-table discover_plugins-table-list<?php echo ($i != count($data['onlinePlugins'])) ? ' padding-0' : ''; ?>">
			<a href="?s=discover_plugins&amp;id=<?php echo $plugin['id']; ?>">
				<table class="table table-borderless<?php echo ($i%2 != 0) ? ' table-reverse' : ''; ?>">
					<tr>
						<td><strong><?php echo $plugin['name']; ?></strong><span><?php _e('Version %s', $plugin['latestVersion']['name']); ?></span><?php if (isset($data['plugins'][$plugin['id']])) { ?><strong class="<?php if (isset($data['availableUpdates'][$plugin['id']])) echo 'orange'; else echo ($data['plugins'][$plugin['id']]['disabled'] == true) ? 'red' : 'green'; ?>"><?php if (isset($data['availableUpdates'][$plugin['id']])) echo 'Update'; else  echo ($data['plugins'][$plugin['id']]['disabled'] == true) ? 'Deaktiviert' : 'Installiert'; ?></strong><?php } ?></td>
						<td><?php echo $plugin['description']; ?></td>
					</tr>
				</table>
			</a>
		</div>
<?php } ?>
	</div>
</div>