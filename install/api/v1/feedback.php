<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/rpi.function.php')						or die('Error: 0x0002');
(include_once LIBRARY_PATH.'install/install.function.php')				or die('Error: 0x0003');

setGlobalLanguage();

$stats = array();

// Pi Control Cron
$cronEntry = '* * * * * '.exec('whoami').' php -f "'.CRON_PATH.'init.php" >/dev/null 2>&1 # By Pi Control';

exec('cat /etc/crontab', $crontab);
$cronMatch = preg_match('/^\*\s\*\s\*\s\*\s\*\s'.preg_quote(exec('whoami')).'\sphp \-f "'.preg_quote(CRON_PATH, '/').'init\.php"( )?(# By Pi Control)?/im', implode(PHP_EOL, $crontab));

// Dateien und Ordner
$filesFoldersExist = array('count' => 0, 'status' => true);
$filesFoldersPermission = array('count' => 0, 'status' => true);
$filesFolders = fileFolderPermission();

foreach ($filesFolders as $file => $info)
{
	if ($info['error'] === true)
	{
		if ($info['existsBool'] === false || $info['filesizeBool'] === false)
		{
			$filesFoldersExist['count'] += 1;
			$filesFoldersExist['status'] = false;
		}
		
		if ($info['permissionBool'] === false || $info['userGroupBool'] === false)
		{
			$filesFoldersPermission['count'] += 1;
			$filesFoldersPermission['status'] = false;
		}
	}
}

$stats['version'] = $config['version']['versioncode'];
$stats['language'] = $globalLanguage;
$stats['url'] = urldecode($_POST['url']);
$stats['path'] = PICONTROL_PATH;
$stats['php'] = PHP_VERSION;
$stats['webserver'] = $_SERVER['SERVER_SOFTWARE'];
$stats['filesFoldersExist'] = $filesFoldersExist;
$stats['filesFoldersPermission'] = $filesFoldersPermission;
$stats['piControlCron'] = array('match' => $cronMatch, 'paketStatus' => trim(exec('dpkg -s php5-cli | grep Status: ')));
$stats['whoami'] = exec('whoami');
$stats['lastStart'] = time() - rpi_getRuntime();
$stats['serverAddr'] = $_SERVER['SERVER_ADDR'];
$stats['serverPort'] = $_SERVER['SERVER_PORT'];
$stats['distribution'] = rpi_getDistribution();
$stats['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
$stats['config'] = array('installed' => 'false');

echo urlencode(base64_encode(json_encode($stats)));
?>