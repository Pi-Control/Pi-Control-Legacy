<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'trouble-shooting/trouble-shooting.function.php') or die($error_code['0x0006']);
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
$cronEntry = '* * * * * www-data php -f "'.CRON_PATH.'init.php" # By Pi Control';

exec('cat /etc/crontab', $crontab);
$cronMatch = preg_match('/^\*\s\*\s\*\s\*\s\*\swww\-data\sphp \-f "'.preg_quote(CRON_PATH, '/').'init\.php"( )?(# By Pi Control)?/im', implode("\n", $crontab));

$lastExecutionLog = array(
						filemtime(LOG_PATH.'statistic/coretemp.csv'),
						filemtime(LOG_PATH.'statistic/cpuload.csv'),
						filemtime(LOG_PATH.'statistic/ram.csv'),
						filemtime(LOG_PATH.'statistic/network_eth0.csv')
					);

rsort($lastExecutionLog);

if (isset($_POST['cronSubmit']) && $_POST['cronSubmit'] != '')
{
	addCronToCrontab($cronEntry, $ssh); // TODO
}

$tpl->assign('filesFolders', $filesFolders);
$tpl->assign('filesFoldersError', $filesFoldersError);
$tpl->assign('cronEntry', $cronEntry);
$tpl->assign('cronMatch', $cronMatch);
$tpl->assign('cronPHPCLI', ($cronPHPCLI = (trim(exec('dpkg -s php5-cli | grep Status: ')) != '') ? true : false));
$tpl->assign('cronLastExecution', formatTime(getConfig('cron:execution.cron', 0)));
$tpl->assign('cronLastExecutionBool', ($cronLastExecutionBool = (getConfig('cron:execution.cron', 0) > time()-150) ? true : false));
$tpl->assign('cronLastExecutionLog', formatTime($lastExecutionLog[0]));
$tpl->assign('cronLastExecutionLogBool', ($cronLastExecutionLogBool = ($lastExecutionLog[0] > time()-330) ? true : false));
$tpl->assign('cronPermission', $filesFolders['resources/cron/init.php']['permission']);
$tpl->assign('cronPermissionBool',($cronPermissionBool =  $filesFolders['resources/cron/init.php']['permissionBool']));
$tpl->assign('cronUserGroup', $filesFolders['resources/cron/init.php']['userGroup']);
$tpl->assign('cronUserGroupBool', ($cronUserGroupBool = $filesFolders['resources/cron/init.php']['userGroupBool']));
$tpl->assign('cronError', ($cronMatch !== 1) ? 1 : (($cronPHPCLI !== true || $cronLastExecutionBool !== true || $cronLastExecutionLogBool !== true || $cronPermissionBool !== true || $cronUserGroupBool !== true) ? 2 : 0));

$tpl->draw('settings/trouble-shooting');
?>