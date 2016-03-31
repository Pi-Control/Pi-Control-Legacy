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
			case 'time':
				$api->addData('time', date('d.m.Y H:i:s', time()));
					break;
			case 'timezone':
				$api->addData('timezone', date('e (P)', time()));
					break;
			case 'runtime':
				$api->addData('runtime', getDateFormat(rpi_getRuntime()));
					break;
			case 'startTime':
				$api->addData('startTime', formatTime(time() - rpi_getRuntime()));
					break;
			case 'serial':
				$api->addData('serial', rpi_getRpiSerial());
					break;
			case 'revision':
				$api->addData('revision', rpi_getRpiRevision());
					break;
			case 'distribution':
				$api->addData('distribution', rpi_getDistribution());
					break;
			case 'kernel':
				$api->addData('kernel', rpi_getKernelVersion());
					break;
			case 'webserver':
				$api->addData('webserver', $_SERVER['SERVER_SOFTWARE']);
					break;
			case 'php':
				$api->addData('php', PHP_VERSION);
					break;
			case 'whoami':
				$api->addData('whoami', exec('whoami'));
					break;
			case 'cpuClock':
				$api->addData('cpuClock', rpi_getCpuClock());
					break;
			case 'cpuClockMax':
				$api->addData('cpuClockMax', rpi_getCpuMaxClock());
					break;
			case 'cpuLoad':
				$cpu = rpi_getCPULoad(true, false);
				$api->addData('cpuLoad', $cpu['cpu']);
					break;
			case 'cpuLoads':
				$cpu = rpi_getCPULoad(true, true);
				$api->addData('cpuLoads', (count($cpu) == 1) ? array() : array_slice($cpu, 1));
					break;
			case 'cpuType':
				$api->addData('cpuType', rpi_getCPUType());
					break;
			case 'cpuModel':
				$api->addData('cpuModel', rpi_getCpuModel());
					break;
			case 'cpuTemp':
				$api->addData('cpuTemp', numberFormat(rpi_getCoreTemprature()));
					break;
			case 'ramPercentage':
				$ram = rpi_getMemoryUsage();
				$api->addData('ramPercentage', $ram['percent']);
					break;
			case 'memory':
				$memory = rpi_getMemoryInfo();
				$api->addData('memory', $memory);
					break;
			case 'memoryCount':
				$memory = rpi_getMemoryInfo();
				$api->addData('memoryCount', count($memory));
					break;
			case 'allUsers':
				$users = new Cache('users', 'rpi_getAllUsers');
				$api->addData('allUsers', array('data' => $users->getContent(), 'hint' => $users->displayHint(true)));
					break;
			case 'runningTasksCount':
				$api->addData('runningTasksCount', rpi_getCountRunningTasks());
					break;
			case 'installedPackagesCount':
				$api->addData('installedPackagesCount', rpi_getCountInstalledPackages());
					break;
			default:
				$api->setError('error', 'Data for "'.$data.'" is not available.');
		}
	}
}
else
	$api->setError('error', 'No data set.');

$api->display();
?>