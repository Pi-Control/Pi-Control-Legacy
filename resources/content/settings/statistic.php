<?php
$tpl->setHeaderTitle(_t('Einstellungen - Statistik'));

$folder = LOG_PATH.'/statistic/';
$fileArray = array();
$logArray = array();
$statistics = array();
$hiddenStatistics = array_filter(explode('~', $tpl->getConfig('config_statistic_hide', '')));

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
	elseif (substr($file , 0, -4) == 'ram')
		$statistics[] = 'ram';
	elseif (substr($file , 0, 8) == 'network_')
		$statistics[] = substr($file, 0, -4);
}
	
if (!isset($_GET['reset']))
{
	if (isset($_POST['submit']))
	{
		$hiddenStatistics = array_diff($statistics, (isset($_POST['check'])) ? $_POST['check'] : array());
		
		if (($set_config_statistic_hide = setConfigValue('config_statistic_hide', '\''.implode('~', $hiddenStatistics).'\'')) === 0)
			$tpl->msg('green', '', 'Die Einstellungen wurden erfolgreich gespeichert.');
		else
			$tpl->msg('red', '', $error_code['0x0043'].$set_config_statistic_hide);
	}
	
	foreach ($fileArray as $file)
	{
		if (substr($file , 0, -4) == 'coretemp')
		{
			$logArray[] = array('log' => 'coretemp',
								'label' => 'CPU-Temperatur',
								'size' => filesize($folder.'/'.$file),
								'display' => (array_search('coretemp', $hiddenStatistics) !== false) ? 0 : 1);
			
			$statistics[] = 'coretemp';
		}
		elseif (substr($file , 0, -4) == 'cpuload')
		{
			$logArray[] = array('log' => 'cpuload',
								'label' => 'CPU-Auslastung',
								'size' => filesize($folder.'/'.$file),
								'display' => (array_search('cpuload', $hiddenStatistics) !== false) ? 0 : 1);
			
			$statistics[] = 'coretemp';
		}
		elseif (substr($file , 0, -4) == 'ram')
		{
			$logArray[] = array('log' => 'ram',
								'label' => 'RAM-Auslastung',
								'size' => filesize($folder.'/'.$file),
								'display' => (array_search('ram', $hiddenStatistics) !== false) ? 0 : 1);
			
			$statistics[] = 'coretemp';
		}
		elseif (substr($file , 0, 8) == 'network_')
		{
			$logArray[] = array('log' => substr($file, 0, -4),
								'label' => substr($file , 8, -4),
								'size' => filesize($folder.'/'.$file),
								'display' => (array_search(substr($file, 0, -4), $hiddenStatistics) !== false) ? 0 : 1);
			
			$statistics[] = substr($file, 0, -4);
		}
	}
	
	$tpl->assign('logArray', $logArray);
	
	$tpl->draw('settings/statistic');
}
else
{
	if (array_search(urldecode($_GET['reset']), $statistics) === false)
		$tpl->msg('red', '', $error_code['2x0013'].urldecode($_GET['reset']));
	
	if (isset($_GET['confirm']) && $_GET['confirm'] == '')
	{
		if (array_search(urldecode($_GET['reset']), $statistics) !== false)
		{
			if (($logFile = fopen(LOG_PATH.'/'.urldecode($_GET['reset']).'.log.txt', 'w')) !== false)
				$tpl->msg('green', '', 'Verlauf wurde erfolgreich zurückgesetzt.');
			else
				$tpl->msg('red', '', 'Verlauf konnte nicht zurückgesetzt werden.');
			
			fclose($logFile);
		}
	}
	
	$label = substr(urldecode($_GET['reset']), 8);
	
	switch (urldecode($_GET['reset']))
	{
		case 'coretemp': $label = 'CPU-Temperatur'; break;
		case 'cpuload': $label = 'CPU-Auslastung'; break;
		case 'ram': $label = 'RAM-Auslastung'; break;
	}
	
	$tpl->assign('log', $_GET['reset']);
	$tpl->assign('label', $label);
	
	$tpl->draw('settings/statistic_reset');
}
?>