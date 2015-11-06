<?php
$fileName = 'statistik';

if (isset($_GET['label']))
	$fileName = preg_replace('/[^a-zA-Z0-9_-]+/', '', $_GET['label']);
else
	exit();

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=".$fileName.".csv");
header("Pragma: no-cache");
header("Expires: 0");

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/statistic/statistic.class.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'/statistic/statistic.function.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');

$log = new LogStatistic();
$log->setFile(LOG_PATH.'/statistic/'.$_GET['log'].'.csv');

function convertTimestampToISO(&$value, $key)
{
	$value[0] = date('c', trim($value[0]));
}

switch ($_GET['type'])
{
	case 'coretemp': // CPU-Temperatur
		$header = array('Datum', 'Temperatur in Grad Celsius');
		$output = fopen('php://output', 'w');
		
		$data = $log->getAll();
		array_walk($data, 'convertTimestampToISO');
		
		fputcsv($output, $header);
		
		foreach ($data as $entry)
			fputcsv($output, $entry);
		
		fclose($output);
			break;
	
	case 'cpuload': // CPU-Auslastung
		$header = array('Datum', 'Auslastung in Prozent');
		$output = fopen('php://output', 'w');
		
		$data = $log->getAll();
		array_walk($data, 'convertTimestampToISO');
		
		fputcsv($output, $header);
		
		foreach ($data as $entry)
			fputcsv($output, $entry);
		
		fclose($output);
			break;
	
	case 'ram': // Arbeitsspeicher
		$header = array('Datum', 'Auslastung in Prozent');
		$output = fopen('php://output', 'w');
		
		$data = $log->getAll();
		array_walk($data, 'convertTimestampToISO');
		
		fputcsv($output, $header);
		
		foreach ($data as $entry)
			fputcsv($output, $entry);
		
		fclose($output);
			break;
	
	case 'network': // Netzwerk
		$header = array('Datum', 'Gesendet in Byte', 'Empfangen in Byte');
		$output = fopen('php://output', 'w');
		
		$data = $log->getAll();
		array_walk($data, 'convertTimestampToISO');
		
		fputcsv($output, $header);
		
		foreach ($data as $entry)
			fputcsv($output, $entry);
		
		fclose($output);
			break;
}

if (file_exists(LOG_PATH.'/statistic/'.$_GET['log'].'.csv') && is_file(LOG_PATH.'/statistic/'.$_GET['log'].'.csv') && filesize(LOG_PATH.'/statistic/'.$_GET['log'].'.csv') == 0)
	header("HTTP/1.0 412");
?>