<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')				or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'statistic/statistic.function.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');

$api = new API;

if (isset($_POST['data'], $_POST['type']))
{
	$log = new LogStatistic();
	$log->setFile(LOG_PATH.'statistic/'.$_POST['data'].'.csv');
	
	switch ($_POST['type'])
	{
		case 'coretemp': // CPU-Temperatur
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => 'Zeit', 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => 'Temperatur', 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'coretemp');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = round(max($info['values']) * 1.05);
				$arr['min'] = round(min($info['values']) * 0.95);
				$arr['periods'] = $info['periods'];
			}
				break;
		
		case 'cpuload': // CPU-Auslastung
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => 'Zeit', 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => 'Auslastung', 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'cpuload');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = 100;
				$arr['min'] = 0.01;
				$arr['periods'] = $info['periods'];
			}
				break;
		
		case 'ram': // Arbeitsspeicher
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => 'Zeit', 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => 'Auslastung', 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'ram');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = 100;
				$arr['min'] = 0.01;
				$arr['periods'] = $info['periods'];
			}
				break;
		
		case 'network': // Netzwerk
			$arr = $info = array();
			$arr['cols'][] = array('id' => '', 'label' => 'Zeit', 'type' => 'datetime');
			$arr['cols'][] = array('id' => '', 'label' => 'Gesendet', 'type' => 'number');
			$arr['cols'][] = array('id' => '', 'label' => 'Empfangen', 'type' => 'number');
			
			getRowsFromLog($arr, $info, $log->getAll(), 'network');
			
			if (isset($arr['rows']))
			{
				$arr['rows'] = array_slice($arr['rows'], -2016);
				$arr['max'] = round(((max($info['up']) > max($info['down'])) ? max($info['up']) : max($info['down'])) * 1.10);
				$arr['min'] = round(((min($info['up']) > min($info['down'])) ? min($info['down']) : min($info['up'])) * 0.90);
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
								'label' => 'CPU-Temperatur',
								'type' => 'coretemp',
								'title' => 'Grad Celsius',
								'unit' => ' °C',
								'columns' => array(1));
		}
		if (substr($file_ , 0, -4) == 'cpuload' && array_search('cpuload', $hiddenStatistics) === false)
		{
			$logArray[] = array('log' => 'cpuload',
								'label' => 'CPU-Auslastung',
								'type' => 'cpuload',
								'title' => 'Auslastung %',
								'unit' => ' %',
								'columns' => array(1));
		}
		if (substr($file_ , 0, -4) == 'ram' && array_search('ram', $hiddenStatistics) === false)
		{
			$logArray[] = array('log' => 'ram',
								'label' => 'RAM-Auslastung',
								'type' => 'ram',
								'title' => 'Auslastung %',
								'unit' => ' %',
								'columns' => array(1));
		}
		elseif (substr($file_ , 0, 8) == 'network_' && array_search(substr($file_ , 0, -4), $hiddenStatistics) === false)
		{
			$logArray[] = array('log' => substr($file_, 0, -4),
								'label' => substr($file_ , 8, -4),
								'type' => 'network',
								'title' => 'Traffic (MB)',
								'unit' => ' MB',
								'columns' => array(1,2));
		}
	}
	
	$api->addData('statistics', $logArray);
	$api->addData('hidden', $hiddenStatistics);
}

$api->display();
?>