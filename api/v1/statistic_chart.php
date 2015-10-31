<?php
(include_once realpath(dirname(__FILE__)).'/../../resources/init.php') or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/statistic/statistic.class.php')		or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'/statistic/statistic.function.php')		or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');

$log = new LogStatistic();
$log->setFile(LOG_PATH.'/statistic/'.$_GET['log'].'.csv');

switch ($_GET['type'])
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
			$arr['min'] = 0;
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
			$arr['min'] = 0;
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
}

if (file_exists(LOG_PATH.'/statistic/'.$_GET['log'].'.csv') && is_file(LOG_PATH.'/statistic/'.$_GET['log'].'.csv') && filesize(LOG_PATH.'/statistic/'.$_GET['log'].'.csv') == 0)
	header("HTTP/1.0 412");
else
	print(json_encode($arr, JSON_NUMERIC_CHECK));
?>