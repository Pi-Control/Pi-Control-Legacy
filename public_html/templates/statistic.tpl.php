<?php if (!defined('PICONTROL')) exit(); ?>
<noscript>
<div>
	<div class="info_red box">
		<div class="inner">
			<strong>Bitte aktiviere JavaScript, um dir die Statistiken anzusehen.</strong>
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
<?php if(empty($data['logArray']) && $data['logArrayCount'] > 0) { ?>
		<div class="inner-info">
			<div><?php _e('Alle Statistiken sind ausgeblendet!'); ?></div>
		</div>
<?php } elseif (empty($data['logArray'])) { ?>
		<div class="inner-info">
			<div><?php _e('Es sind noch keine Statistiken verfügbar. Werte werden alle 5 Minuten eingetragen.'); ?></div>
		</div>
<?php } ?>
	</div>
</div>
<div class="clear-both"></div>
<div class="order-3">
<?php foreach ($data['logArray'] as $value) { ?>
	<div class="box google-controls" id="dashboard_log_<?php echo $value['log']; ?>">
		<div class="inner-header">
			<span><?php _e($value['label']); ?></span>
		</div>
		<div class="inner text-center padding-0" id="chart_log_<?php echo $value['log']; ?>">
			<img src="public_html/img/loader2.svg" style="margin: 20px;" />
		</div>
		<div class="inner text-center" id="chart_control_log_<?php echo $value['log']; ?>">
		</div>
	</div>
<?php } ?>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load('visualization', '1', {packages:['controls']});
	google.setOnLoadCallback(createTable);
	
	function createTable()
	{
<?php foreach ($data['logArray'] as $value) { ?>
		var jsonData = $.ajax({
			url: "api/v1/statistic_chart.php?type=<?php echo $value['type']; ?>&log=<?php echo $value['log']; ?>",
			dataType:"json",
			async: true
		}).done(function(data)
		{
			period_<?php echo $value['log']; ?> = data.periods;
			
			var myData = new google.visualization.DataTable(data);
			
			$('#chart_log_<?php echo $value['log']; ?>').html('');
			var myDashboard = new google.visualization.Dashboard(document.getElementById('dashboard_log_<?php echo $value['log']; ?>'));
			
			myDateSlider_<?php echo $value['log']; ?> = new google.visualization.ControlWrapper({
				'controlType': 'DateRangeFilter',
				'containerId': 'chart_control_log_<?php echo $value['log']; ?>',
				'options': {
					'filterColumnLabel': 'Zeit',
					'ui': {
						'step': 'hour',
						'label': '',
						'format': { 'pattern': 'dd.MM. HH:mm' }
					}
				}
			});
			
			var myLine = new google.visualization.ChartWrapper({
				'chartType' : 'AreaChart',
				'containerId' : 'chart_log_<?php echo $value['log']; ?>',
				'options': {
					vAxis: { viewWindow: { max: data.max, min: data.min }, textPosition: 'in', textStyle: { fontSize: 11, color: '#AAAAAA' }, gridlines: { color: '#fff' } },
					dateFormat: 'dd.MM.yy HH:mm',
					hAxis: { format: 'dd.MM. HH:mm', textPosition: 'out', gridlines: { color: '#fff' } , textStyle: { fontSize: 11, color: '#AAAAAA' }},
					focusTarget: 'category',
					crosshair: { orientation: 'vertical', trigger: 'both', color: '#AAAAAA', opacity: 0.4 },
					chartArea: {width: '100%', height: '80%', top: 0},
					legend: {position: 'bottom', alignment: 'end', textStyle: { fontSize: 11, color: '#AAAAAA' } },
				}
			});
			
			var formatter = new google.visualization.NumberFormat(
				{ suffix: '<?php echo $value['unit']; ?>' }
			);
<?php foreach ($value['columns'] as $value2) { ?>
			formatter.format(myData, <?php echo $value2; ?>);
<?php } ?>
			myDashboard.bind(myDateSlider_<?php echo $value['log']; ?>, myLine);
			myDashboard.draw(myData);
		}).fail(function(xhr, textStatus)
		{
			if (xhr.status == 412)
				$('#chart_log_<?php echo $value['log']; ?>').html('<br /><br /><strong class="red"><?php _e('Es sind noch keine Werte verfügbar. Werte werden alle 5 Minuten eingetragen.'); ?></strong>');
			else
				$('#chart_log_<?php echo $value['log']; ?>').html('<br /><br /><strong class="red"><?php _e('Es ist ein Fehler aufgetreten! Fehlercode: %s', '\'+xhr.status+\''); ?></strong>');
		});
<?php } ?>
	}
	
	function changeRange(dropdown) {
		switch (dropdown.value)
		{
			case 'seven':
<?php foreach ($data['logArray'] as $value) { ?>
				if (typeof myDateSlider_<?php echo $value['log']; ?> != 'undefined')
				{
	        		myDateSlider_<?php echo $value['log']; ?>.setState({'lowValue': new Date(period_<?php echo $value['log']; ?>.seven)});
	        		myDateSlider_<?php echo $value['log']; ?>.draw();
				}
<?php } ?>
					break;
			case 'six':
<?php foreach ($data['logArray'] as $value) { ?>
				if (typeof myDateSlider_<?php echo $value['log']; ?> != 'undefined')
				{
	        		myDateSlider_<?php echo $value['log']; ?>.setState({'lowValue': new Date(period_<?php echo $value['log']; ?>.six)});
	        		myDateSlider_<?php echo $value['log']; ?>.draw();
				}
<?php } ?>
					break;
			case 'five':
<?php foreach ($data['logArray'] as $value) { ?>
				if (typeof myDateSlider_<?php echo $value['log']; ?> != 'undefined')
				{
	        		myDateSlider_<?php echo $value['log']; ?>.setState({'lowValue': new Date(period_<?php echo $value['log']; ?>.five)});
	        		myDateSlider_<?php echo $value['log']; ?>.draw();
				}
<?php } ?>
					break;
			case 'four':
<?php foreach ($data['logArray'] as $value) { ?>
				if (typeof myDateSlider_<?php echo $value['log']; ?> != 'undefined')
				{
	        		myDateSlider_<?php echo $value['log']; ?>.setState({'lowValue': new Date(period_<?php echo $value['log']; ?>.four)});
	        		myDateSlider_<?php echo $value['log']; ?>.draw();
				}
<?php } ?>
					break;
			case 'three':
<?php foreach ($data['logArray'] as $value) { ?>
				if (typeof myDateSlider_<?php echo $value['log']; ?> != 'undefined')
				{
	        		myDateSlider_<?php echo $value['log']; ?>.setState({'lowValue': new Date(period_<?php echo $value['log']; ?>.three)});
	        		myDateSlider_<?php echo $value['log']; ?>.draw();
				}
<?php } ?>
					break;
			case 'two':
<?php foreach ($data['logArray'] as $value) { ?>
				if (typeof myDateSlider_<?php echo $value['log']; ?> != 'undefined')
				{
	        		myDateSlider_<?php echo $value['log']; ?>.setState({'lowValue': new Date(period_<?php echo $value['log']; ?>.two)});
	        		myDateSlider_<?php echo $value['log']; ?>.draw();
				}
<?php } ?>
					break;
			case 'one':
<?php foreach ($data['logArray'] as $value) { ?>
				if (typeof myDateSlider_<?php echo $value['log']; ?> != 'undefined')
				{
	        		myDateSlider_<?php echo $value['log']; ?>.setState({'lowValue': new Date(period_<?php echo $value['log']; ?>.one)});
	        		myDateSlider_<?php echo $value['log']; ?>.draw();
				}
<?php } ?>
					break;
				
		}
    }
</script>