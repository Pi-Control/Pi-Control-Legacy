<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'troubleshooting/troubleshooting.function.php') or die('Error: 0x0010');
$tpl->setHeaderTitle(_t('Problembehandlung'));

// Dateien und Ordner
$filesFolders = fileFolderPermission();
$filesFoldersError = false;

foreach ($filesFolders as $file => $info)
{
	if ($info['error'] === true)
	{
		$filesFoldersError = true;
		break;
	}
}

// Cron
$cronEntry = '* * * * * '.exec('whoami').' php -f "'.CRON_PATH.'init.php" >/dev/null 2>&1 # By Pi Control';

exec('cat /etc/crontab', $crontab);
$cronMatch = preg_match('/^\*\s\*\s\*\s\*\s\*\s'.preg_quote(exec('whoami')).'\sphp \-f "'.preg_quote(CRON_PATH, '/').'init\.php"( )?(# By Pi Control)?/im', implode(PHP_EOL, $crontab));

$lastExecutionLog = array(
						(file_exists(LOG_PATH.'statistic/coretemp.csv')) ? filemtime(LOG_PATH.'statistic/coretemp.csv') : 1,
						(file_exists(LOG_PATH.'statistic/cpuload.csv')) ? filemtime(LOG_PATH.'statistic/cpuload.csv') : 1,
						(file_exists(LOG_PATH.'statistic/ram.csv')) ? filemtime(LOG_PATH.'statistic/ram.csv') : 1,
						(file_exists(LOG_PATH.'statistic/network_eth0.csv')) ? filemtime(LOG_PATH.'statistic/network_eth0.csv') : 1
					);

rsort($lastExecutionLog);

$tpl->assign('filesFolders', $filesFolders);
$tpl->assign('filesFoldersError', $filesFoldersError);
$tpl->assign('configHelp', $config['url']['help']);
$tpl->assign('cronEntry', $cronEntry);
$tpl->assign('cronMatch', $cronMatch);
$tpl->assign('cronPHPCLI', ($cronPHPCLI = (trim(exec('dpkg -s php5-cli | grep Status: ')) != '' || trim(exec('dpkg -s php7.0-cli | grep Status: ')) != '') ? true : false));
$tpl->assign('cronLastExecution', formatTime(getConfig('cron:execution.cron', 0)));
$tpl->assign('cronLastExecutionBool', ($cronLastExecutionBool = (getConfig('cron:execution.cron', 0) > time()-150) ? true : false));
$tpl->assign('cronLastExecutionLog', formatTime($lastExecutionLog[0]));
$tpl->assign('cronLastExecutionLogBool', ($cronLastExecutionLogBool = ($lastExecutionLog[0] > time()-330) ? true : false));
$tpl->assign('cronPermission', $filesFolders['resources/cron/init.php']['permission']);
$tpl->assign('cronPermissionBool',($cronPermissionBool =  $filesFolders['resources/cron/init.php']['permissionBool']));
$tpl->assign('cronUserGroup', $filesFolders['resources/cron/init.php']['userGroup']);
$tpl->assign('cronUserGroupBool', ($cronUserGroupBool = $filesFolders['resources/cron/init.php']['userGroupBool']));
$tpl->assign('cronCharacterEncoding', trim(exec('file /etc/crontab -b')));
$tpl->assign('cronCharacterEncodingBool', ($cronCharacterEncodingBool = trim(exec('file /etc/crontab -b') == 'ASCII text') ? true : false));
$tpl->assign('cronError', ($cronMatch !== 1) ? 1 : (($cronPHPCLI !== true || $cronLastExecutionBool !== true || $cronLastExecutionLogBool !== true || $cronPermissionBool !== true || $cronUserGroupBool !== true || $cronCharacterEncodingBool !== true ) ? 2 : 0));

$tpl->draw('settings/troubleshooting');
?>