<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Einstellungen zur Statistik'));

$folder = LOG_PATH.'statistic/';
$fileArray = array();
$logArray = array();
$statistics = array();
$hiddenStatistics = unserialize(htmlspecialchars_decode(getConfig('main:statistic.hidden', 'a:0:{}')));

foreach (@scandir($folder) as $file)
{
	if ($file[0] != '.')
	{
		if (is_file($folder.'/'.$file) && substr($file, -4) == '.csv')
			$fileArray[] = $file;
	}
}

foreach ($fileArray as $file)
{
	if (substr($file , 0, -4) == 'coretemp')
		$statistics[] = 'coretemp';
	elseif (substr($file , 0, -4) == 'cpuload')
		$statistics[] = 'cpuload';
	elseif (substr($file , 0, -4) == 'cpufrequency')
		$statistics[] = 'cpufrequency';
	elseif (substr($file , 0, -4) == 'memory')
		$statistics[] = 'memory';
	elseif (substr($file , 0, -4) == 'ram')
		$statistics[] = 'ram';
	elseif (substr($file , 0, 8) == 'network_')
		$statistics[] = substr($file, 0, -4);
}
	
if (!isset($_GET['reset']) && (!isset($_GET['download']) || !isset($_GET['type']) || !isset($_GET['log']) || !isset($_GET['label'])))
{
	if (isset($_POST['submit']))
	{
		$hiddenStatistics = array_diff($statistics, (isset($_POST['check'])) ? $_POST['check'] : array());
		
		if (setConfig('main:statistic.hidden', htmlspecialchars(serialize($hiddenStatistics))) !== false)
			$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'));
		else
			$tpl->msg('error', _t('Fehler'), _t('Konnte Wert nicht in Konfigurationsdatei speichern!'));
	}
	
	foreach ($fileArray as $file)
	{
		if (substr($file , 0, -4) == 'coretemp')
		{
			$logArray[] = array('log' => 'coretemp',
								'label' => _t('CPU-Temperatur'),
								'type' => 'coretemp',
								'display' => (array_search('coretemp', $hiddenStatistics) !== false) ? 0 : 1);
			
			$statistics[] = 'coretemp';
		}
		elseif (substr($file , 0, -4) == 'cpuload')
		{
			$logArray[] = array('log' => 'cpuload',
								'label' => _t('CPU-Auslastung'),
								'type' => 'cpuload',
								'display' => (array_search('cpuload', $hiddenStatistics) !== false) ? 0 : 1);
			
			$statistics[] = 'coretemp';
		}
		elseif (substr($file , 0, -4) == 'ram')
		{
			$logArray[] = array('log' => 'ram',
								'label' => _t('RAM-Auslastung'),
								'type' => 'ram',
								'display' => (array_search('ram', $hiddenStatistics) !== false) ? 0 : 1);
			
			$statistics[] = 'coretemp';
		}
		elseif (substr($file , 0, 8) == 'network_')
		{
			$logArray[] = array('log' => substr($file, 0, -4),
								'label' => substr($file , 8, -4),
								'type' => 'network',
								'display' => (array_search(substr($file, 0, -4), $hiddenStatistics) !== false) ? 0 : 1);
			
			$statistics[] = substr($file, 0, -4);
		}
		elseif (substr($file , 0, -4) == 'cpufrequency')
		{
			$logArray[] = array('log' => 'cpufrequency',
								'label' => _t('CPU-Takt'),
								'type' => 'cpufrequency',
								'display' => (array_search('cpufrequency', $hiddenStatistics) !== false) ? 0 : 1);
		}
		elseif (substr($file , 0, -4) == 'memory')
		{
			$logArray[] = array('log' => 'memory',
								'label' => _t('Speicherverbrauch'),
								'type' => 'memory',
								'display' => (array_search('memory', $hiddenStatistics) !== false) ? 0 : 1);
		}
	}
	
	$tpl->assign('logArray', $logArray);
	
	$tpl->draw('settings/statistic');
}
elseif (isset($_GET['reset']))
{
	if (array_search(urldecode($_GET['reset']), $statistics) === false)
		$tpl->msg('error', _t('Fehler'), _t('Der Verlauf konnte nicht gefunden werden: %s', urldecode($_GET['reset'])));
	
	if (isset($_GET['confirm']) && $_GET['confirm'] == '')
	{
		if (array_search(urldecode($_GET['reset']), $statistics) !== false)
		{
			if (($logFile = fopen($folder.urldecode($_GET['reset']).'.csv', 'w')) !== false)
			{
				$tpl->msg('success', _t('Verlauf zur&uuml;ckgesetzt'), _t('Verlauf wurde erfolgreich zur&uuml;ckgesetzt.'));
				fclose($logFile);
			}
			else
				$tpl->msg('error', _t('Fehler'), _t('Verlauf konnte nicht zur&uuml;ckgesetzt werden.'));
		}
	}
	
	$label = substr(urldecode($_GET['reset']), 8);
	
	switch (urldecode($_GET['reset']))
	{
		case 'coretemp': $label = _t('CPU-Temperatur'); break;
		case 'cpuload': $label = _t('CPU-Auslastung'); break;
		case 'ram': $label = _t('RAM-Auslastung'); break;
	}
	
	$tpl->assign('log', $_GET['reset']);
	$tpl->assign('label', $label);
	
	$tpl->draw('settings/statistic_reset');
}
elseif (isset($_GET['download'], $_GET['type'], $_GET['log'], $_GET['label']))
{
	$tpl->redirect('api/v1/statistic_download.php?log='.$_GET['log'].'&type='.$_GET['type'].'&label='.$_GET['label']);
}
?>