<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'plugin/plugin.function.php')				or die('Error: 0x0001');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0002');

$api = new API;

$plugins = pluginList();

$onlinePlugins = getOnlinePlugins();
$availableUpdates = checkPluginUpdate($plugins, $onlinePlugins);

$api->addData('onlinePlugins', $onlinePlugins);
$api->addData('availableUpdates', $availableUpdates);

$api->display();
?>