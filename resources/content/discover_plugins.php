<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Plugins entdecken'));

$showList = true;

$plugins = pluginList();
$onlinePlugins = getOnlinePlugins();
$availableUpdates = checkPluginUpdate($plugins, $onlinePlugins);

if (!is_array($onlinePlugins))
{
	$tpl->error('Fehler beim Abrufen', 'Leider ist beim Abrufen der Plugins ein Fehler aufgetreten. Fehlercode: '.$onlinePlugins);
	$showList = false;
}

if (isset($_GET['id']) && $_GET['id'] != '')
{
	if (isset($onlinePlugins[$_GET['id']]))
	{
		$showList = false;
		include_once 'discover_plugins_info.php';
	}
	else
		$tpl->msg('error', '', 'Das von dir gesuchte Plugin konnte nicht gefunden werden.');
}

if ($showList == true)
{
	$disabledPluginsCount = 0;
	foreach ($plugins as $plugin)
	{
		if ($plugin['disabled'] == true)
			$disabledPluginsCount += 1;
	}
	
	$tpl->assign('plugins', $plugins);
	$tpl->assign('onlinePlugins', $onlinePlugins);
	$tpl->assign('availableUpdates', $availableUpdates);
	$tpl->assign('disabledPluginsCount', $disabledPluginsCount);

	$tpl->draw('discover_plugins');
}
?>