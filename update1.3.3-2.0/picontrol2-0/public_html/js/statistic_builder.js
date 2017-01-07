var dataSlider = [];
var dataPeriods = [];

function statisticBuilder(builder, plugin = null)
{
	var jsonData = jQuery.ajax({
		url: 'api/v1/statistic.php',
		method: 'POST',
		data: { id: builder.id, plugin: plugin, type: 'googleChart' },
		dataType: 'json',
		async: true
	}).done(function(data)
	{
		if (data.error != null)
		{
			if (data.error.message == 'Empty data.')
				jQuery('#chart_log_'+builder.id).html('<br /><br /><strong class="red">' + _t('Es sind noch keine Werte verf&uuml;gbar. Werte werden alle %%s Minuten eingetragen.', builder.cycle) + '</strong>');
			else
				jQuery('#chart_log_'+builder.id).html('<br /><br /><strong class="red">' + _t('Es ist ein Fehler aufgetreten! Fehler: %%s', data.error.message) + '</strong>');
			
			return;
		}
		
		dataPeriods[builder.id] = data.data.statistic.periods;
		
		var myData = new google.visualization.DataTable(data.data.statistic);
		
		jQuery('#chart_log_'+builder.id).html('');
		var myDashboard = new google.visualization.Dashboard(document.getElementById('dashboard_log_'+builder.id));
		
		dataSlider[builder.id] = new google.visualization.ControlWrapper({
			'controlType': 'DateRangeFilter',
			'containerId': 'chart_control_log_'+builder.id,
			'options': {
				'filterColumnLabel': _t('Zeit'),
				'ui': {
					'step': 'hour',
					'label': '',
					'format': { 'pattern': 'dd.MM. HH:mm' }
				}
			}
		});
		
		var t = null;
		
		var myLine = new google.visualization.ChartWrapper({
			'chartType' : 'AreaChart',
			'containerId' : 'chart_log_'+builder.id,
			'options': {
				vAxis: { title: builder.label, viewWindow: { max: data.data.statistic.max, min: data.data.statistic.min }, textPosition: 'in', textStyle: { fontSize: 11, color: '#AAAAAA' }, titleTextStyle:  { fontSize: 11, color: '#AAAAAA' }, gridlines: { color: '#fff' } },
				dateFormat: 'dd.MM.yy HH:mm',
				hAxis: { format: 'dd.MM. HH:mm', textPosition: 'out', gridlines: { color: '#fff' } , textStyle: { fontSize: 11, color: '#AAAAAA' }},
				focusTarget: 'category',
				crosshair: { orientation: 'vertical', trigger: 'both', color: '#AAAAAA', opacity: 0.4 },
				chartArea: { width: '100%', height: '80%', top: 0 },
				height: 200,
				legend: { position: 'bottom', alignment: 'end', textStyle: { fontSize: 11, color: '#AAAAAA' } },
				axisTitlesPosition: 'in',
				seriesType: 'area',
				series:
				{
					0: {
						type: (typeof builder.columns[1] != 'undefined' && typeof builder.columns[1].style != 'undefined' && typeof builder.columns[1].style.type != 'undefined') ? builder.columns[1].style.type : null,
						color: (typeof builder.columns[1] != 'undefined' && typeof builder.columns[1].style != 'undefined' && typeof builder.columns[1].style.color != 'undefined') ? builder.columns[1].style.color : null,
						lineDashStyle: (typeof builder.columns[1] != 'undefined' && typeof builder.columns[1].style != 'undefined' && typeof builder.columns[1].style.lineDashStyle != 'undefined') ? [builder.columns[1].style.lineDashStyle, builder.columns[1].style.lineDashStyle] : null
					},
					1: {
						type: (typeof builder.columns[2] != 'undefined' && typeof builder.columns[2].style != 'undefined' && typeof builder.columns[2].style.type != 'undefined') ? builder.columns[2].style.type : null,
						color: (typeof builder.columns[2] != 'undefined' && typeof builder.columns[2].style != 'undefined' && typeof builder.columns[2].style.color != 'undefined') ? builder.columns[2].style.color : null,
						lineDashStyle: (typeof builder.columns[2] != 'undefined' && typeof builder.columns[2].style != 'undefined' && typeof builder.columns[2].style.lineDashStyle != 'undefined') ? [builder.columns[2].style.lineDashStyle, builder.columns[2].style.lineDashStyle] : null
					},
					2: {
						type: (typeof builder.columns[3] != 'undefined' && typeof builder.columns[3].style != 'undefined' && typeof builder.columns[3].style.type != 'undefined') ? builder.columns[3].style.type : null,
						color: (typeof builder.columns[3] != 'undefined' && typeof builder.columns[3].style != 'undefined' && typeof builder.columns[3].style.color != 'undefined') ? builder.columns[3].style.color : null,
						lineDashStyle: (typeof builder.columns[3] != 'undefined' && typeof builder.columns[3].style != 'undefined' && typeof builder.columns[3].style.lineDashStyle != 'undefined') ? [builder.columns[3].style.lineDashStyle, builder.columns[3].style.lineDashStyle] : null
					},
					3: {
						type: (typeof builder.columns[4] != 'undefined' && typeof builder.columns[4].style != 'undefined' && typeof builder.columns[4].style.type != 'undefined') ? builder.columns[4].style.type : null,
						color: (typeof builder.columns[4] != 'undefined' && typeof builder.columns[4].style != 'undefined' && typeof builder.columns[4].style.color != 'undefined') ? builder.columns[4].style.color : null,
						lineDashStyle: (typeof builder.columns[4] != 'undefined' && typeof builder.columns[4].style != 'undefined' && typeof builder.columns[4].style.lineDashStyle != 'undefined') ? [builder.columns[4].style.lineDashStyle, builder.columns[4].style.lineDashStyle] : null
					},
				}
			    
			}
		});
		
		var formatter = new google.visualization.NumberFormat({ suffix: ' '+builder.unit });
		
		for (var i = 1; i < builder.columns.length; i++)
			formatter.format(myData, i);
		
		myDashboard.bind(dataSlider[builder.id], myLine);
		myDashboard.draw(myData);
	}).fail(function(xhr, textStatus)
	{
		jQuery('#chart_log_'+builder.id).html('<br /><br /><strong class="red">' + _t('Es ist ein Fehler aufgetreten! Fehlercode: %%s', xhr.status) + '</strong>');
	});
}

function changeRange(dropdown)
{
	var days = Array('seven', 'six', 'five', 'four', 'three', 'two', 'one');
	var day = dropdown.value;
	
	if (days.indexOf(day) > -1)
	{
		for (var data in dataSlider)
		{
			if (typeof dataSlider[data] != 'undefined')
			{
				dataSlider[data].setState({'lowValue': new Date(dataPeriods[data][day])});
				dataSlider[data].draw();
			}
		}
	}
}