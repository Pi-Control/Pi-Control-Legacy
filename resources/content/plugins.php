<?php
$tpl->setHeaderTitle(_t('Plugins'));

$plugins = pluginList();
$pluginLoaded = false;

if (isset($_GET['id']))
{
	if (isset($plugins[$_GET['id']]))
	{
		$plugin = $plugins[$_GET['id']];
		
		$pluginLoaded = true;
		$tpl->setTplFolder('resources/plugins/'.$plugin['id'].'/public_html/templates/');
		
		if (isset($_GET['settings']))
		{
			if ($plugin['settings'] === true)
				include PLUGINS_PATH.'/'.$plugin['id'].'/resources/content/settings/settings.php';
			else
				$tpl->msg('error', 'Fehler beim Laden des Plugins', 'Das gesuchte Plugin unterstützt momentan keine Einstellungen.', true);
		}
		else
			include PLUGINS_PATH.'/'.$plugin['id'].'/resources/content/index.php';
		
		$tpl->setTplFolder('public_html/templates/');
	}
	else
		$tpl->msg('error', 'Plugin nicht gefunden', 'Das gesuchte Plugin kann aktuell nicht gefunden werden oder es existiert nicht.', true);
}

if ($pluginLoaded === false)
{
	$tpl->assign('plugins', $plugins);
	$tpl->draw('plugins');
}
?>