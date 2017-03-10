<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Error: 0x0001');

if (date('i', time()) % 5 == 0)
{
	sleep(7);
	$cpuClock = shell_exec('cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq');
}

$folder = CRON_PATH;
$fileArray = array();

foreach (@scandir($folder) as $file)
{
	if ($file[0] != '.')
	{
		if (is_file($folder.'/'.$file) && $file != 'init.php')
			$fileArray[] = $file;
	}
}

foreach ($fileArray as $file)
{
	$timeOfFile = str_replace('-', '', substr($file, 0, 2));
	$rest = date('i', time()) % $timeOfFile;
	
	if (is_numeric($rest) && $rest == 0)
	{
		exec('/usr/bin/php -f "'.CRON_PATH.$file.'"'.((isset($cpuClock) && $cpuClock !== false && $file == '5-cpufrequency.php') ? ' '.escapeshellarg($cpuClock) : ''));
		set_time_limit(30);
		usleep(500000);
	}
}

if (trim(exec('dpkg -s php5-cli | grep Status: ')) != '' || trim(exec('dpkg -s php7.0-cli | grep Status: ')) != '')
	setConfig('cron:execution.cron', time());
?>