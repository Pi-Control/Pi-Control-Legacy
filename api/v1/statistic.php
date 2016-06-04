<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')				or die('Error: 0x0002');
(include_once LIBRARY_PATH.'statistic/statistic.function.php')			or die('Error: 0x0003');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0004');

$api = new API;

if (isset($_POST['data'], $_POST['type']))
{
	$log = new LogStatistic();
	$log->setFile(LOG_PATH.'statistic/'.$_POST['data'].'.csv');
	
	switch ($_POST['type'])
	{
		case 'coretemp':
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => _t('Zeit'), 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => _t('Temperatur'), 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'coretemp');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = round(max($info['values']) * 1.05);
				$arr['min'] = round(min($info['values']) * 0.95);
				$arr['periods'] = $info['periods'];
			}
				break;
		
		case 'cpuload':
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => _t('Zeit'), 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => _t('Auslastung'), 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'cpuload');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = 100;
				$arr['min'] = 0.01;
				$arr['periods'] = $info['periods'];
			}
				break;
		
		case 'ram':
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => _t('Zeit'), 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => _t('Auslastung'), 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'ram');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = 100;
				$arr['min'] = 0.01;
				$arr['periods'] = $info['periods'];
			}
				break;
		
		case 'network':
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => _t('Zeit'), 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => _t('Gesendet'), 'type' => 'number');
			$arr['cols'][] = array('id' => '', 'label' => _t('Empfangen'), 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'network');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = round(((max($info['up']) > max($info['down'])) ? max($info['up']) : max($info['down'])) * 1.10);
				$arr['min'] = round(((min($info['up']) > min($info['down'])) ? min($info['down']) : min($info['up'])) * 0.90);
				$arr['periods'] = $info['periods'];
			}
				break;
		
		case 'cpufrequency':
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => _t('Zeit'), 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => _t('Takt'), 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'cpufrequency');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = 1200;
				$arr['min'] = 0.01;
				$arr['periods'] = $info['periods'];
			}
				break;
		
		case 'memory':
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => _t('Zeit'), 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => _t('Gesamt'), 'type' => 'number');
			$arr['cols'][] = array('id' => '', 'label' => _t('Belegt'), 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'memory');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = round(((max($info['total']) > max($info['used'])) ? max($info['total']) : max($info['used'])) * 1.10);
				$arr['min'] = 0.01;
				$arr['periods'] = $info['periods'];
			}
				break;
		
		default:
			$api->setError('error', 'Data for "'.$_POST['data'].'" are not available.');
	}

	if (file_exists(LOG_PATH.'statistic/'.$_POST['data'].'.csv') && is_file(LOG_PATH.'statistic/'.$_POST['data'].'.csv') && filesize(LOG_PATH.'/statistic/'.$_POST['data'].'.csv') == 0)
		$api->setError('error', 'Empty data.');
	else
		$api->addData('statistic', $arr);
}
else
{
	$folder = LOG_PATH.'statistic';
	$fileArray = array();
	$logArray = array();
	$hiddenStatistics = unserialize(htmlspecialchars_decode(getConfig('main:statistic.hidden', 'a:0:{}')));

	foreach (@scandir($folder) as $file)
	{
		if ($file[0] != '.')
		{
			if (is_file($folder.'/'.$file) && substr($file, -4) == '.csv')
				$fileArray[] = $file;
		}
	}

	foreach ($fileArray as $file_)
	{
		if (substr($file_ , 0, -4) == 'coretemp' && array_search('coretemp', $hiddenStatistics) === false)
		{
			$logArray[] = array('log' => 'coretemp',
								'label' => _t('CPU-Temperatur'),
								'type' => 'coretemp',
								'title' => _t('Grad Celsius'),
								'unit' => ' °C',
								'columns' => array(1));
		}
		if (substr($file_ , 0, -4) == 'cpuload' && array_search('cpuload', $hiddenStatistics) === false)
		{
			$logArray[] = array('log' => 'cpuload',
								'label' => _t('CPU-Auslastung'),
								'type' => 'cpuload',
								'title' => _t('Auslastung %%'),
								'unit' => ' %',
								'columns' => array(1));
		}
		if (substr($file_ , 0, -4) == 'ram' && array_search('ram', $hiddenStatistics) === false)
		{
			$logArray[] = array('log' => 'ram',
								'label' => _t('RAM-Auslastung'),
								'type' => 'ram',
								'title' => _t('Auslastung %'),
								'unit' => ' %',
								'columns' => array(1));
		}
		elseif (substr($file_ , 0, 8) == 'network_' && array_search(substr($file_ , 0, -4), $hiddenStatistics) === false)
		{
			$logArray[] = array('log' => substr($file_, 0, -4),
								'label' => substr($file_ , 8, -4),
								'type' => 'network',
								'title' => _t('Traffic (MB)'),
								'unit' => ' MB',
								'columns' => array(1,2));
		}
	}
	
	$api->addData('statistics', $logArray);
	$api->addData('hidden', $hiddenStatistics);
}

$api->display();
?>