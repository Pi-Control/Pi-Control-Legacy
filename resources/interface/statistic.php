<?php
(include_once realpath(dirname(__FILE__)).'/../main_config.php') or die(print(json_encode(array('status' => 600))));
(include_once LIBRARY_PATH.'/main/functions.php') or die(print(json_encode(array('status' => 601))));
(include_once LIBRARY_PATH.'/main/functions_statistic.php') or die(print(json_encode(array('status' => 602))));
(include_once LIBRARY_PATH.'/main/classes.php') or die(print(json_encode(array('status' => 603))));

if (isset($_GET['type'], $_GET['log']))
{
	$log = new Logging();
	$log->setFile(LOG_PATH.'/'.$_GET['log'].'.log.txt');
	$logArray = $log->getAll();
	
	$counter = (isset($_GET['limit']) ? (($_GET['limit'] > 2016) ? 2016 : $_GET['limit']) : 2016);
	
	switch ($_GET['type'])
	{
		case 'coretemp':
			$arr = array();
			Interface_getRowsFromLog($arr, $logArray, 'coretemp');
			
			if (isset($arr['rows']))
				$arr['rows'] = array_slice($arr['rows'], -$counter);
				break;
		
		case 'cpuload':
			$arr = array();
			Interface_getRowsFromLog($arr, $logArray, 'cpuload');
			
			if (isset($arr['rows']))
				$arr['rows'] = array_slice($arr['rows'], -$counter);
				break;
		
		case 'ram':
			$arr = array();
			Interface_getRowsFromLog($arr, $logArray, 'ram');
			
			if (isset($arr['rows']))
				$arr['rows'] = array_slice($arr['rows'], -$counter);
				break;
		
		case 'network':
			$arr = array();
			Interface_getRowsFromLog($arr, $logArray, 'network');
			
			if (isset($arr['rows']))
				$arr['rows'] = array_slice($arr['rows'], -$counter);
				break;
	}
	
	if (file_exists(LOG_PATH.'/'.$_GET['log'].'.log.txt') && is_file(LOG_PATH.'/'.$_GET['log'].'.log.txt') && filesize(LOG_PATH.'/'.$_GET['log'].'.log.txt') == 0)
		header("HTTP/1.0 412");
	else
		print(json_encode($arr, JSON_NUMERIC_CHECK));
}
else
{
	$folder = LOG_PATH;
	$fileArray = array();
	$logArray = array();
	$hiddenStatistics = array_filter(explode('~', getConfigValue('config_statistic_hide')));
	
	foreach (@scandir($folder) as $file)
	{
		if ($file[0] != '.')
		{
			if (is_file($folder.'/'.$file) && substr($file, -8) == '.log.txt')
				$fileArray[] = $file;
		}
	}
	
	foreach ($fileArray as $file_)
	{
		if (substr($file_ , 0, -8) == 'coretemp')
		{
			$logArray[] = array('log' => 'coretemp',
								'label' => 'CPU-Temperatur',
								'type' => 'coretemp');
		}
		if (substr($file_ , 0, -8) == 'cpuload')
		{
			$logArray[] = array('log' => 'cpuload',
								'label' => 'CPU-Auslastung',
								'type' => 'cpuload');
		}
		if (substr($file_ , 0, -8) == 'ram')
		{
			$logArray[] = array('log' => 'ram',
								'label' => 'RAM-Auslastung',
								'type' => 'ram');
		}
		elseif (substr($file_ , 0, 8) == 'network_')
		{
			$logArray[] = array('log' => substr($file_, 0, -8),
								'label' => substr($file_ , 8, -8),
								'type' => 'network');
		}
	}
	
	print(json_encode(array('all' => $logArray, 'hidden' => $hiddenStatistics, 'status' => 200)));
}
?>