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
			if ($plugin['compatible'] == true)
			{
				$pluginLoaded = true;
				$tpl->setTplFolderPlugin('resources/plugins/'.$plugin['id']);
				
				define('PLUGIN_ID', $plugin['id']);
				define('PLUGIN_PATH', PLUGINS_PATH.$plugin['id'].'/');
				define('PLUGIN_PUBLIC_PATH', str_replace(PICONTROL_PATH, '', PLUGINS_PATH.$plugin['id'].'/'));
				
				$tpl->setHeaderTitle(_t($plugin['name']));
				
				if (file_exists(PLUGIN_PATH.'resources/library/main/sites.php') && is_file(PLUGIN_PATH.'resources/library/main/sites.php'))
					include PLUGIN_PATH.'resources/library/main/sites.php';
				
				if (isset($_GET['settings']))
				{
					if ($plugin['settings'] === true)
						include PLUGIN_PATH.'resources/content/settings/settings.php';
					else
						$tpl->msg('error', _t('Fehler beim Laden des Plugins'), _t('Das gesuchte Plugin unterst&uuml;tzt momentan keine Einstellungen.'), false);
				}
				elseif (isset($pluginSite, $_GET['do']) && isset($pluginSite[$_GET['do']]) && file_exists(PLUGIN_PATH.'resources/content/'.$pluginSite[$_GET['do']]))
					include PLUGIN_PATH.'resources/content/'.$pluginSite[$_GET['do']];
				else
					include PLUGIN_PATH.'resources/content/index.php';
				
				$tpl->setTplFolderPlugin('');
			}
			else
				$tpl->msg('error', _t('Plugin ist inkompatibel'), _t('Das gesuchte Plugin kann aktuell nicht ge&ouml;ffnet werden, da es inkompatibel ist. Bitte aktualisiere dein Pi Control, um das Plugin weiterhin verwenden zu k&ouml;nnen.'), true);
		}
		else
			$tpl->msg('error', _t('Plugin ist deaktiviert'), _t('Das gesuchte Plugin kann aktuell nicht ge&ouml;ffnet werden, da es deaktiviert ist.'));
	}
	else
		$tpl->msg('error', _t('Plugin nicht gefunden'), _t('Das gesuchte Plugin kann aktuell nicht gefunden werden oder es existiert nicht.'));
}

if ($pluginLoaded === false)
{
	$plugins = array_filter($plugins, function($plugin) { if ($plugin['disabled'] == true) return false; return true; });
	
	$tpl->assign('plugins', $plugins);
	$tpl->draw('plugins');
}
?>