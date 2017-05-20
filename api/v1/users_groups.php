<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'api/api.class.php')								or die('Error: 0x0001');
(include_once LIBRARY_PATH.'cache/cache.class.php')							or die('Error: 0x0002');

$api = new API;

$users = new Cache('users', 'rpi_getAllUsers');
$users->load();
$api->addData('users', $users->getContent());
$api->addData('hint', $users->displayHint(true));

$api->display();
?>