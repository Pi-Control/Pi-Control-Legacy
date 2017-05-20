<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'api/api.class.php')								or die('Error: 0x0001');
(include_once LIBRARY_PATH.'process/process.function.php')					or die('Error: 0x0002');
(include_once LIBRARY_PATH.'process/process.class.php')						or die('Error: 0x0003');

$api = new API;

$processController = new ProcessController;
$api->addData('count', $processController->getCount());
$api->addData('countRunning', $processController->getCountRunning());
$api->addData('processes', $processController->getProcessesArray());

$api->display();
?>