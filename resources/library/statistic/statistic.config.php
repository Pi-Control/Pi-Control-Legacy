<?php
$statisticConfig = array(
	'coretemp' => array(
		'title' => 'CPU-Temperatur',
		'label' => 'Grad Celsius',
		'unit' => '°C',
		'cycle' => 5,
		'columns' => array(
			array(
				'label' => 'Zeit',
				'type' => 'datetime',
				'downloadTitle' => 'Datum'
			),
			array(
				'label' => 'Temperatur',
				'type' => 'number',
				'downloadTitle' => 'Temperatur in Grad Celsius'
			)
		),
		'limits' => array(
			'min' => array(
				'value' => 0.95,
				'use' => 'multiply',
				'fix' => true
			),
			'max' => array(
				'value' => 1.05,
				'use' => 'multiply',
				'fix' => true
			)
		)
	),
	'cpuload' => array(
		'title' => 'CPU-Auslastung',
		'label' => 'Auslastung %%',
		'unit' => '%',
		'cycle' => 5,
		'columns' => array(
			array(
				'label' => 'Zeit',
				'type' => 'datetime',
				'downloadTitle' => 'Datum'
			),
			array(
				'label' => 'Auslastung',
				'type' => 'number',
				'downloadTitle' => 'Auslastung in Prozent'
			)
		),
		'limits' => array(
			'min' => array(
				'value' => 0.01,
				'use' => 'fix',
				'fix' => true
			),
			'max' => array(
				'value' => 100,
				'use' => 'fix',
				'fix' => true
			)
		)
	),
	'cpufrequency' => array(
		'title' => 'CPU-Takt',
		'label' => 'MHz',
		'unit' => 'MHz',
		'cycle' => 5,
		'columns' => array(
			array(
				'label' => 'Zeit',
				'type' => 'datetime',
				'downloadTitle' => 'Datum'
			),
			array(
				'label' => 'Takt',
				'type' => 'number',
				'downloadTitle' => 'Auslastung in MHz'
			)
		),
		'limits' => array(
			'min' => array(
				'value' => 0.01,
				'use' => 'fix',
				'fix' => true
			),
			'max' => array(
				'value' => 1200,
				'use' => 'fix',
				'fix' => true
			)
		)
	),
	'ram' => array(
		'title' => 'RAM-Auslastung',
		'label' => 'Auslastung %%',
		'unit' => '%',
		'cycle' => 5,
		'columns' => array(
			array(
				'label' => 'Zeit',
				'type' => 'datetime',
				'downloadTitle' => 'Datum'
			),
			array(
				'label' => 'Auslastung',
				'type' => 'number',
				'downloadTitle' => 'Auslastung in Prozent'
			)
		),
		'limits' => array(
			'min' => array(
				'value' => 0.01,
				'use' => 'fix',
				'fix' => true
			),
			'max' => array(
				'value' => 100,
				'use' => 'fix',
				'fix' => true
			)
		)
	),
	'network' => array(
		'title' => 'Netzwerkdaten - %%s',
		'label' => 'Daten (MB)',
		'unit' => 'MB',
		'cycle' => 5,
		'columns' => array(
			array(
				'label' => 'Zeit',
				'type' => 'datetime',
				'downloadTitle' => 'Datum'
			),
			array(
				'label' => 'Gesendet',
				'type' => 'number',
				'downloadTitle' => 'Gesendet in Byte',
				'division' => 1048576
			),
			array(
				'label' => 'Empfangen',
				'type' => 'number',
				'downloadTitle' => 'Empfangen in Byte',
				'division' => 1048576
			)
		),
		'limits' => array(
			'min' => array(
				'value' => 0.90,
				'use' => 'multiply',
				'fix' => false
			),
			'max' => array(
				'value' => 1.10,
				'use' => 'multiply',
				'fix' => false
			)
		)
	),
	'network_packets' => array(
		'title' => 'Netzwerkpakete - %%s',
		'label' => 'Pakete',
		'unit' => 'Pakete',
		'cycle' => 5,
		'columns' => array(
			array(
				'label' => 'Zeit',
				'type' => 'datetime',
				'downloadTitle' => 'Datum'
			),
			array(
				'label' => 'Gesendet',
				'type' => 'number',
				'downloadTitle' => 'Gesendete Pakete'
			),
			array(
				'label' => 'Empfangen',
				'type' => 'number',
				'downloadTitle' => 'Empfangene Pakete'
			)
		),
		'limits' => array(
			'min' => array(
				'value' => 0.90,
				'use' => 'multiply',
				'fix' => false
			),
			'max' => array(
				'value' => 1.10,
				'use' => 'multiply',
				'fix' => false
			)
		)
	),
	'memory' => array(
		'title' => 'Speicherverbrauch',
		'label' => 'MB',
		'unit' => 'MB',
		'cycle' => 5,
		'columns' => array(
			array(
				'label' => 'Zeit',
				'type' => 'datetime',
				'downloadTitle' => 'Datum'
			),
			array(
				'label' => 'Gesamt',
				'type' => 'number',
				'downloadTitle' => 'Gesamt in Byte',
				'division' => 1048576,
				'style' => array(
					'color' => '#3366cc',
					'type' => 'line',
					'lineDashStyle' => 10
				)
			),
			array(
				'label' => 'Belegt',
				'type' => 'number',
				'downloadTitle' => 'Belegt in Byte',
				'division' => 1048576,
				'style' => array(
					'color' => '#3366cc'
				)
			)
		),
		'limits' => array(
			'min' => array(
				'value' => 0.01,
				'use' => 'fix',
				'fix' => true
			),
			'max' => array(
				'value' => 1.10,
				'use' => 'multiply',
				'fix' => false
			)
		)
	)
);
?>