<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'process/process.function.php')	or die('Error: 0x0010');
(include_once LIBRARY_PATH.'process/process.class.php')		or die('Error: 0x0011');

$tpl->setHeaderTitle(_t('Prozesse'));

$processController = new ProcessController;


$tpl->assign('processCount', $processController->getCount());
$tpl->assign('processCountRunning', $processController->getCountRunning());
$tpl->assign('processes', $processController->getProcesses());

$tpl->draw('processes');

?>