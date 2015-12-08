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
		$tpl->setTplFolderPlugin('resources/plugins/'.$plugin['id']);
		
		if (file_exists(PLUGINS_PATH.'/'.$plugin['id'].'/resources/library/sites.php') && is_file(PLUGINS_PATH.'/'.$plugin['id'].'/resources/library/sites.php'))
			include PLUGINS_PATH.'/'.$plugin['id'].'/resources/library/sites.php';
		
		if (isset($_GET['settings']))
		{
			if ($plugin['settings'] === true)
				include PLUGINS_PATH.'/'.$plugin['id'].'/resources/content/settings/settings.php';
			else
				$tpl->msg('error', 'Fehler beim Laden des Plugins', 'Das gesuchte Plugin unterstützt momentan keine Einstellungen.', true);
		}
		elseif (isset($pluginSite) && isset($pluginSite[$_GET['do']]) && file_exists(PLUGINS_PATH.'/'.$plugin['id'].'/resources/content/'.$pluginSite[$_GET['do']]))
			include PLUGINS_PATH.'/'.$plugin['id'].'/resources/content/'.$pluginSite[$_GET['do']];
		else
			include PLUGINS_PATH.'/'.$plugin['id'].'/resources/content/index.php';
		
		$tpl->setTplFolderPlugin('');
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