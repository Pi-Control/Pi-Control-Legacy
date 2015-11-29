<div>
	<div class="box">
		<div class="inner-header">
			<span>Einstellungen zu Plugins</span>
		</div>
<?php
$i = 0; foreach ($data['plugins'] as $plugin)
{
	$i++;
?>
		<div class="inner-table settings-plugins-table-list<?php echo ($i != count($data['plugins'])) ? ' padding-0' : ''; ?>">
			<table class="table table-borderless<?php echo ($i%2 != 0) ? ' table-reverse' : ''; ?>">
				<tr>
					<td><strong><?php _e($plugin['name']); ?></strong><span><?php _e('Version %s', $plugin['version']['name']); ?></span></td>
					<td><a href="#" class="text-decoration-none"><span class="button button-small">Einstellungen</span></a>
						<a href="#" class="text-decoration-none"><span class="button button-small">Deaktivieren</span></a>
						<a href="#" class="text-decoration-none"><span class="button button-small">Entfernen</span></a></td>
					<td><?php _e($plugin['description']); ?></td>
				</tr>
			</table>
		</div>
<?php
}
?>
	</div>
</div>