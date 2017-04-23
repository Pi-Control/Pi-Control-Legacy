<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'process/process.function.php')	or die('Error: 0x0010');
(include_once LIBRARY_PATH.'process/process.class.php')		or die('Error: 0x0011');

$tpl->setHeaderTitle(_t('Prozesse'));

$processController = new ProcessController;

if (isset($_POST['terminate'], $_POST['pid'], $_POST['startTime']) && $_POST['terminate'] != '' && ($pid = $_POST['pid']) != '' && ($startTime = $_POST['startTime']) != '')
{
	if ($pid > 0)
	{
		if (ProcessController::isPidWithStartTimeExists($pid, $startTime))
		{
			if ($processController->terminatePid($pid, $startTime))
				$tpl->msg('success', 'Prozess beendet', _t('Der Prozess mit der ID %s wurde erfolgreich beendet.', $pid));
			else
				$tpl->msg('error', 'Fehler', _t('Der Prozess mit der ID %s konnte nicht beendet werden.', $pid));
		}
		else
			$tpl->msg('error', 'Fehler', _t('Der Prozess mit der ID %s konnte nicht gefunden werden.', $pid));
	}
}
elseif (isset($_POST['kill'], $_POST['pid'], $_POST['startTime']) && $_POST['kill'] != '' && ($pid = $_POST['pid']) != '' && ($startTime = $_POST['startTime']) != '')
{
	if ($pid > 0)
	{
		if (ProcessController::isPidWithStartTimeExists($pid, $startTime))
		{
			if ($processController->killPid($pid, $startTime))
				$tpl->msg('success', 'Prozess abgew&uuml;rgt', _t('Der Prozess mit der ID %s wurde erfolgreich abgew&uuml;rgt.', $pid));
			else
				$tpl->msg('error', 'Fehler', _t('Der Prozess mit der ID %s konnte nicht abgew&uuml;rgt werden.', $pid));
		}
		else
			$tpl->msg('error', 'Fehler', _t('Der Prozess mit der ID %s konnte nicht gefunden werden.', $pid));
	}
}

$tpl->assign('processCount', $processController->getCount());
$tpl->assign('processCountRunning', $processController->getCountRunning());
$tpl->assign('processes', $processController->getProcesses());

$tpl->draw('processes');

?>