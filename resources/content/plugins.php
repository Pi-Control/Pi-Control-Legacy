<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Plugins'));

$plugins = pluginList();
$pluginLoaded = false;

if (isset($_GET['id']))
{
	if (isset($plugins[$_GET['id']]))
	{
		$plugin = $plugins[$_GET['id']];
		
		if ($plugin['disabled'] == false)
		{
			if ($plugin['disabled'] == false)
			{
				$pluginLoaded = true;
				$tpl->setTplFolderPlugin('resources/plugins/'.$plugin['id']);
				
				if (file_exists(PLUGINS_PATH.$plugin['id'].'/resources/library/sites.php') && is_file(PLUGINS_PATH.$plugin['id'].'/resources/library/sites.php'))
					include PLUGINS_PATH.$plugin['id'].'/resources/library/sites.php';
				
				if (isset($_GET['settings']))
				{
					if ($plugin['settings'] === true)
						include PLUGINS_PATH.$plugin['id'].'/resources/content/settings/settings.php';
					else
						$tpl->msg('error', _t('Fehler beim Laden des Plugins'), _t('Das gesuchte Plugin unterst&uuml;tzt momentan keine Einstellungen.'), true);
				}
				elseif (isset($pluginSite, $_GET['do']) && isset($pluginSite[$_GET['do']]) && file_exists(PLUGINS_PATH.$plugin['id'].'/resources/content/'.$pluginSite[$_GET['do']]))
					include PLUGINS_PATH.$plugin['id'].'/resources/content/'.$pluginSite[$_GET['do']];
				else
					include PLUGINS_PATH.$plugin['id'].'/resources/content/index.php';
				
				$tpl->setTplFolderPlugin('');
			}
			else
				$tpl->msg('error', _t('Plugin ist inkompatibel'), _t('Das gesuchte Plugin kann aktuell nicht ge&ouml;ffnet werden, da es inkompatibel ist. Bitte aktualisiere das Plugin, um es weiterhin verwenden zu k&ouml;nnen.'), true);
		}
		else
			$tpl->msg('error', _t('Plugin ist deaktiviert'), _t('Das gesuchte Plugin kann aktuell nicht ge&ouml;ffnet werden, da es deaktiviert ist.'), true);
	}
	else
		$tpl->msg('error', _t('Plugin nicht gefunden'), _t('Das gesuchte Plugin kann aktuell nicht gefunden werden oder es existiert nicht.'), true);
}

if ($pluginLoaded === false)
{
	$plugins = array_filter($plugins, function($plugin) { if ($plugin['disabled'] == true) return false; return true; });
	
	$tpl->assign('plugins', $plugins);
	$tpl->draw('plugins');
}
?>