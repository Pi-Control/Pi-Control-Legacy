<?php if (!defined('PICONTROL')) exit(); ?>
<noscript>
<div>
	<div class="info_red box">
		<div class="inner">
			<strong><?php _e('Bitte aktiviere JavaScript, um dir die Statistiken anzusehen.'); ?></strong>
		</div>
	</div>
</div>
</noscript>
<div class="sidebar order-2">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Zeitraum'); ?></span>
		</div>
		<div class="inner">
			<select onchange="changeRange(this)">
				<option name="" value="seven"><?php _e('Alles (7 Tage)'); ?></option>
				<option name="" value="six"><?php _e('Letzten 6 Tage'); ?></option>
				<option name="" value="five"><?php _e('Letzten 5 Tage'); ?></option>
				<option name="" value="four"><?php _e('Letzten 4 Tage'); ?></option>
				<option name="" value="three"><?php _e('Letzten 3 Tage'); ?></option>
				<option name="" value="two"><?php _e('Letzten 2 Tage'); ?></option>
				<option name="" value="one"><?php _e('Letzten 24 Stunden'); ?></option>
			</select>
		</div>
	</div>
</div>
<!-- Container -->
<div class="container-600 order-1">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Statistik'); ?></span>
			<?php showSettingsIcon('?s=settings&amp;do=statistic'); ?>
		</div>
<?php if ($data['msgInfo'] == 'invisible') { ?>
		<div class="inner-info">
			<div><?php _e('Alle Statistiken sind ausgeblendet!'); ?></div>
		</div>
<?php } elseif ($data['msgInfo'] == 'empty') { ?>
		<div class="inner-info">
			<div><?php _e('Es sind noch keine Statistiken verf&uuml;gbar. Werte werden alle 5 Minuten eingetragen.'); ?></div>
		</div>
<?php } ?>
	</div>
</div>
<div class="clear-both"></div>
<div class="order-3">
<?php foreach ($data['statistics'] as $statistic) { ?>
	<div class="box google-controls" id="dashboard_log_<?php echo $statistic['array']['id']; ?>">
		<div class="inner-header">
			<span><?php _e($statistic['array']['title']); ?></span>
		</div>
		<div class="inner text-center padding-0" id="chart_log_<?php echo $statistic['array']['id']; ?>">
			<img src="public_html/img/loader.svg" style="margin: 20px;" />
		</div>
		<div class="inner text-center" id="chart_control_log_<?php echo $statistic['array']['id']; ?>">
		</div>
	</div>
<?php } ?>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="public_html/js/statistic_builder.js"></script>
<script type="text/javascript">
	google.load('visualization', '1', {packages:['controls']});
	google.setOnLoadCallback(createTable);

	function createTable()
	{
<?php foreach ($data['statistics'] as $statistic) { ?>
		statisticBuilder(<?php echo $statistic['json']; ?>);
<?php } ?>
	}
</script>