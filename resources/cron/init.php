<?php
(include_once realpath(dirname(__FILE__)).'/../main_config.php') or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/main/functions.php') or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');

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

foreach ($fileArray as $file_)
{
	$get_time_of_file = str_replace('-', '', substr($file_, 0, 2));
	$rest = date('i', time()) % $get_time_of_file;
	
	if (is_numeric($rest) && $rest == 0)
	{
		exec('php -f "'.CRON_PATH.'/'.$file_.'"');
		set_time_limit(30);
	}
}

if (trim(exec('dpkg -s php5-cli | grep Status: ')) != '')
	setConfigValue('config_last_cron_execution', time());
?>