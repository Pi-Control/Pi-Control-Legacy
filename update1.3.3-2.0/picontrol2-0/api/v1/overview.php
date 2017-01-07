<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/rpi.function.php')						or die('Error: 0x0002');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0003');

$api = new API;

if (isset($_POST['data']))
{
	$datas = explode(';', $_POST['data']);
	
	foreach ($datas as $data)
	{
		switch ($data)
		{
			case 'startTime':
				$api->addData('startTime', formatTime(time() - rpi_getRuntime()));
					break;
			case 'runtime':
				$api->addData('runtime', getDateFormat(rpi_getRuntime()));
					break;
			case 'cpuClock':
				$api->addData('cpuClock', rpi_getCpuClock());
					break;
			case 'cpuLoad':
				$api->addData('cpuLoad', rpi_getCpuLoad(true));
					break;
			case 'cpuTemp':
				$api->addData('cpuTemp', numberFormat(rpi_getCoreTemprature()));
					break;
			case 'ramPercentage':
				$ram = rpi_getMemoryUsage();
				$api->addData('ramPercentage', $ram['percent']);
					break;
			case 'memoryUsed':
				$memory = rpi_getMemoryInfo();
				$api->addData('memoryUsed', sizeUnit($memory[count($memory)-1]['used']));
					break;
			case 'memoryFree':
				$memory = rpi_getMemoryInfo();
				$api->addData('memoryFree', sizeUnit($memory[count($memory)-1]['free']));
					break;
			case 'memoryTotal':
				$memory = rpi_getMemoryInfo();
				$api->addData('memoryTotal', sizeUnit($memory[count($memory)-1]['total']));
					break;
			case 'memoryPercent':
				$memory = rpi_getMemoryInfo();
				$api->addData('memoryPercent', $memory[count($memory)-1]['percent']);
					break;
			case 'devices':
				$api->addData('devices', rpi_getUsbDevices());
					break;
			default:
				$api->setError('error', 'Data for "'.$data.'" are not available.');
					break 2;
		}
	}
}
else
	$api->setError('error', 'No data set.');

$api->display();
?>