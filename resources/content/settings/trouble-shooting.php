<?php
(include_once LIBRARY_PATH.'/trouble-shooting/trouble-shooting.function.php') or die($error_code['0x0006']);
$tpl->setHeaderTitle(_t('Problembehandlung'));

$cronEntry = '* * * * * www-data php -f "'.CRON_PATH.'init.php" # By Pi Control';

$filesFolders = getFilesWithFullPath(PICONTROL_PATH, true);

$compare = array(
					'index.php' => array(),
					'resources/init.php' => array(),
					'resources/config/cron.config.ini.php' => array(),
					'resources/config/login.config.ini.php' => array(),
					'resources/config/main.config.ini.php' => array(),
					'resources/config/user.config.ini.php' => array(),
					'resources/cron/init.php' => array(),
					'resources/cron/' => array(),
					'resources/log/' => array(),
					'resources/plugins/' => array(),
					PICONTROL_PATH => array()
				);

$filesFolders += $compare;
array_walk($filesFolders, 'getFileFolderStatus');
$filesFolders = array_filter($filesFolders, 'filterFilesFolders', ARRAY_FILTER_USE_BOTH);
ksort($filesFolders);

$lastExecutionLog = array(
						filemtime(LOG_PATH.'statistic/coretemp.csv'),
						filemtime(LOG_PATH.'statistic/cpuload.csv'),
						filemtime(LOG_PATH.'statistic/ram.csv'),
						filemtime(LOG_PATH.'statistic/network_eth0.csv')
					);

rsort($lastExecutionLog);

$tpl->assign('filesFolders', $filesFolders);
$tpl->assign('cronEntry', $cronEntry);
$tpl->assign('cronCrontab', $filesFolders['resources/cron/init.php']['userGroup']);
$tpl->assign('cronPHPCLI', (trim(exec('dpkg -s php5-cli | grep Status: ')) != '') ? true : false);
$tpl->assign('cronLastExecution', formatTime(getConfig('cron:execution.cron', 0)));
$tpl->assign('cronLastExecutionBool', (getConfig('cron:execution.cron', 0) > time()-150) ? true : false);
$tpl->assign('cronLastExecutionLog', formatTime($lastExecutionLog[0]));
$tpl->assign('cronLastExecutionLogBool', ($lastExecutionLog[0] > time()-330) ? true : false);
$tpl->assign('cronPermission', $filesFolders['resources/cron/init.php']['permission']);
$tpl->assign('cronPermissionBool', $filesFolders['resources/cron/init.php']['permissionBool']);
$tpl->assign('cronUserGroup', $filesFolders['resources/cron/init.php']['userGroup']);
$tpl->assign('cronUserGroupBool', $filesFolders['resources/cron/init.php']['userGroupBool']);

$tpl->draw('settings/trouble-shooting');
?>