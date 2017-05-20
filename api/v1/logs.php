<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')							or die('Error: 0x0001');
(include_once LIBRARY_PATH.'api/api.class.php')								or die('Error: 0x0002');
(include_once LIBRARY_PATH.'log/log.function.php')							or die('Error: 0x0003');
(include_once LIBRARY_PATH.'log/log.class.php')								or die('Error: 0x0004');

$tpl = new PiTpl;
$tpl->setTpl($tpl);

$api = new API;

$logController = new LogController('/var/log/');
$api->addData('logs', $logController->getAllArray());

$api->display();
?>