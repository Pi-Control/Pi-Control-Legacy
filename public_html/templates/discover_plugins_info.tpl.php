<?php if (!defined('PICONTROL')) exit(); ?>
<div class="sidebar order-2">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Aktion'); ?></span>
		</div>
		<div class="inner discover_plugins_info-action">
<?php if (empty($data['plugin'])) { ?>
			<a href="?s=discover_plugins&amp;id=<?php echo $data['onlinePlugin']['id']; ?>&amp;install" class="button"><?php _e('Installieren'); ?></a>
<?php } else { ?>
			<a href="?s=plugins&amp;id=<?php echo $data['plugin']['id']; ?>" class="button"><?php _e('Zum Plugin'); ?></a><br />
	<?php if ($data['plugin']['version']['code'] < $data['onlinePlugin']['latestVersion']) { ?>
			<a href="?s=discover_plugins&amp;id=<?php echo $data['plugin']['id']; ?>&amp;update" class="button"><?php _e('Aktualisieren'); ?></a>
	<?php } ?>
			<a href="?s=discover_plugins&amp;id=<?php echo $data['plugin']['id']; ?>&amp;status" class="button"><?php echo ($data['plugin']['disabled'] == true) ? _t('Aktivieren') : _t('Deaktivieren'); ?></a>
			<a href="?s=settings&amp;do=plugins&amp;delete=<?php echo $data['plugin']['id']; ?>" class="button"><?php _e('L&ouml;schen'); ?></a>
<?php } ?>
		</div>
	</div>
</div>
<div class="container-600 order-1">
	<div class="box">
		<div class="inner-header">
			<span><?php echo $data['onlinePlugin']['name']; ?></span>
			<?php showGoBackIcon('?s=discover_plugins'); ?>
		</div>
		<div class="inner">
			<?php if (!empty($data['plugin'])) { ?>
				<strong class="green">Das Plugin ist bereits installiert.</strong>
			<?php if ($data['plugin']['version']['code'] < $data['onlinePlugin']['latestVersion']) { ?>
				<br /><br /><strong class="red"><?php _e('Das Plugin ist nicht mehr aktuell. Version %s ist verf&uuml;gbar!', $data['onlinePlugin']['versions'][$data['onlinePlugin']['latestVersion']]['name']); ?></strong>
			<?php } } ?>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form">
				<tr>
					<td style="width: 30%;"><?php _e('Version'); ?></td>
					<td><?php echo $data['onlinePlugin']['versions'][$data['onlinePlugin']['latestVersion']]['name']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Ver&ouml;ffentlicht am'); ?></td>
					<td><?php echo formatTime($data['onlinePlugin']['date']); ?></td>
				</tr>
				<tr>
					<td><?php _e('Letzte Aktualisierung'); ?></td>
					<td><?php echo formatTime($data['onlinePlugin']['versions'][$data['onlinePlugin']['latestVersion']]['date']); ?></td>
				</tr>
				<tr>
					<td><?php _e('Beschreibung'); ?></td>
					<td><?php echo $data['onlinePlugin']['description']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Sprachen'); ?></td>
					<td><?php echo implode(', ', array_map('getLanguageFromIso', $data['onlinePlugin']['languages'])); ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="clear-both"></div>
<div class="order-3">
<?php if (!empty($data['onlinePlugin']['manual'])) { ?>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Anleitung'); ?></span>
		</div>
		<div class="inner">
			<?php echo $data['onlinePlugin']['manual']; ?>
		</div>
	</div>
<?php }
	if (!empty($data['onlinePlugin']['requirement'])) { ?>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Voraussetzungen'); ?></span>
		</div>
		<div class="inner">
			<?php echo $data['onlinePlugin']['requirement']; ?>
		</div>
	</div>
<?php }
	if (!empty($data['onlinePlugin']['versions'][$data['onlinePlugin']['latestVersion']]['changelog'])) { ?>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('&Auml;nderungen mit Version %s', $data['onlinePlugin']['versions'][$data['onlinePlugin']['latestVersion']]['name']); ?></span>
		</div>
		<div class="inner">
			<?php echo $data['onlinePlugin']['versions'][$data['onlinePlugin']['latestVersion']]['changelog']; ?>
		</div>
	</div>
<?php }
	if (!empty($data['onlinePlugin']['screenshots'])) { ?>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Screenshots'); ?></span>
		</div>
		<div class="inner discover_plugins_info-screenshots">
	<?php foreach ($data['onlinePlugin']['screenshots'] as $screenshot) { ?>
			<a href="<?php echo $screenshot; ?>" target="_blank"><img src="<?php echo $screenshot; ?>" /></a>
	<?php } ?>
		</div>
	</div>
<?php } ?>
</div>