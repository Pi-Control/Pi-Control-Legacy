<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/main/main.function.php')					or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'/main/rpi.function.php')					or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');

if (!isset($_GET['data']))
{
	header("HTTP/1.0 412");
	exit();
}

switch ($_GET['data'])
{
	case 'run_time':
	echo getDateFormat(rpi_getRuntime());
		break;
	case 'cpu_clock':
	echo rpi_getCpuClock();
		break;
	case 'cpu_load':
	echo rpi_getCpuLoad(true);
		break;
	case 'cpu_temp':
	echo numberFormat(rpi_getCoreTemprature());
		break;
	case 'ram_percentage':
	$ram = rpi_getMemoryUsage(); echo $ram['percent'];
		break;
	case 'memory_used':
	$memory = rpi_getMemoryInfo(); echo sizeUnit($memory[count($memory)-1]['used']);
		break;
	case 'memory_free':
	$memory = rpi_getMemoryInfo(); echo sizeUnit($memory[count($memory)-1]['free']);
		break;
	case 'memory_total':
	$memory = rpi_getMemoryInfo(); echo sizeUnit($memory[count($memory)-1]['total']);
		break;
	case 'memory_percent':
	$memory = rpi_getMemoryInfo(); echo $memory[count($memory)-1]['percent'];
		break;
	default:
	echo 'Kein Wert';
}
?>