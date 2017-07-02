<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'log/log.function.php')	or die('Error: 0x0010');
(include_once LIBRARY_PATH.'log/log.class.php')		or die('Error: 0x0011');

$tpl->setHeaderTitle(_t('Logdateien'));

if (isset($_POST['open_file']))
{
	$tpl->redirect('?s=logs&view='.urlencode($_POST['relative_path'].$_POST['filename']));
}

if (isset($_GET['view']) && ($view = trim(urldecode($_GET['view']))) != '')
{
	$logController = new LogController('/var/log');
	$log = $logController->getLogFromRelativePath($view);
	
	if (!$log instanceof LogEntry) {
		$tpl->error(_t('Logdatei nicht gefunden'), _t('Leider konnte die angegebene Logdatei nicht gefunden oder ge&ouml;ffnet werden!'));
	}
	else
	{
		if ($log->getFilesize() > 10240000) {
			$tpl->assign('filesizeError', true);
		} else {
			set_time_limit(60);
			
			$readLog = $logController->readLog($log->getPath());
			
			$tpl->assign('logOutput', $readLog['output']);
			$tpl->assign('logLines', $readLog['lines']);
		}
		
		$tpl->assign('log', $log);
		
		$tpl->draw('logs_view');
	}
}
elseif (isset($_GET['download']))
{
	$tpl->redirect('api/v1/logs_download.php?log='.$_GET['download']);
}
else
{
	$logController = new LogController('/var/log/');
	
	$tpl->assign('logs', $logController->getAll());
	$tpl->assign('sshAvailable', $logController->isSshAvailable());
	
	$tpl->draw('logs');
}
?>