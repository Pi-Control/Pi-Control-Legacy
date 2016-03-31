<?php
if (!defined('PICONTROL')) exit();

function pluginDisabled($pluginId)
{
	if (file_exists(PLUGINS_PATH.$pluginId.'/plugin_disabled.php') && is_file(PLUGINS_PATH.$pluginId.'/plugin_disabled.php'))
		return true;
	
	return false;
}

function pluginConfig($pluginId)
{
	global $config;
	
	if (!file_exists(PLUGINS_PATH.$pluginId.'/plugin.config.php') || !is_file(PLUGINS_PATH.$pluginId.'/plugin.config.php') || !include PLUGINS_PATH.$pluginId.'/plugin.config.php')
		return false;
	
	if (!isset($pluginConfig) || empty($pluginConfig))
		return false;
	
	if ($pluginConfig['id'] != $pluginId)
		return false;
	
	$pluginConfig['disabled'] = pluginDisabled($pluginId);
	
	if (file_exists(PLUGINS_PATH.$pluginId.'/resources/content/settings/settings.php') && is_file(PLUGINS_PATH.$pluginId.'/resources/content/settings/settings.php'))
		$pluginConfig['settings'] = true;
	else
		$pluginConfig['settings'] = false;
	
	if (file_exists(PLUGINS_PATH.$pluginId.'/resources/content/widget/widget.php') && is_file(PLUGINS_PATH.$pluginId.'/resources/content/widget/widget.php'))
		$pluginConfig['widget'] = true;
	else
		$pluginConfig['widget'] = false;
	
	if ($pluginConfig['version']['require'] >= $config['version']['versioncode'])
		$pluginConfig['compatible'] = true;
	else
		$pluginConfig['compatible'] = false;
	
	return $pluginConfig;
}

function pluginLanguage($pluginId)
{
	global $globalLanguage, $globalLanguageArray;
	
	$pluginConfig = pluginConfig($pluginId);
	
	if (!is_array($pluginConfig))
		return false;
	
	$lang = $globalLanguage;
	$langFile = PLUGINS_PATH.$pluginConfig['id'].'/resources/languages/'.$lang.'.php';
	
	if (file_exists($langFile) === true && is_file($langFile) === true)
	{
		if ((include_once $langFile) === 1)
			$globalLanguageArray = array_merge($globalLanguageArray, $langArray);
		else
			return false;
	}
	
	return true;
}

function pluginList($listDisabled = true, $listConfig = true)
{
	if (!file_exists(PLUGINS_PATH) || !is_dir(PLUGINS_PATH))
		return false;
	
	$pluginList = array();
	
	foreach (scandir(PLUGINS_PATH) as $plugin)
	{
		if ($plugin == '.' || $plugin == '..')
			continue;
		
		if (!file_exists(PLUGINS_PATH.$plugin.'/plugin.config.php') || !is_file(PLUGINS_PATH.'/'.$plugin.'/plugin.config.php'))
			continue;
		
		$pluginConfig = pluginConfig($plugin);
		
		if (!is_array($pluginConfig))
			continue;
		
		if ($listDisabled === false && $pluginConfig['disabled'] === true)
			continue;
		
		pluginLanguage($pluginConfig['id']);
		
		if ($listConfig === true)
			$pluginList[$pluginConfig['id']] = $pluginConfig;
		else
			$pluginList[] = $plugin;
	}
	
	return $pluginList;
}

function setPluginStatus($pluginId, $status)
{
	if (empty($pluginId) || !is_bool($status))
		return false;
	
	if ($status == true)
	{
		if (unlink(PLUGINS_PATH.$pluginId.'/plugin_disabled.php') == true)
			return true;
		else
			return false;
	}
	
	if (touch(PLUGINS_PATH.$pluginId.'/plugin_disabled.php') == true)
		return true;
	else
		return false;
}

function deletePlugin($pluginId, $referer = NULL)
{
	if (empty($pluginId))
		return false;
	
	if (file_exists(PLUGINS_PATH.$pluginId.'/uninstall.php') && is_file(PLUGINS_PATH.$pluginId.'/uninstall.php'))
	{
		if ($referer != NULL)
			$tpl->redirect('resources/plugins/'.$pluginId.'/uninstall.php?referer='.urlencode($referer));
	}
	else
	{
		deleteFolder(PLUGINS_PATH.$pluginId.'/');
		
		if ($referer != NULL)
			$tpl->redirect($referer);
	}
	
	return true;
}

function getOnlinePlugins()
{
	global $config, $globalLanguage;
	
	$lang = $globalLanguage;
	
	if (!class_exists('cURL'))
		(include LIBRARY_PATH.'curl/curl.class.php');
	
	$curl = new cURL($config['url']['plugin']);
	$curl->execute();
	
	if ($curl->getStatusCode() != '200')
		return $curl->getStatusCode();
	
	if ($curl->getResult($data) != JSON_ERROR_NONE)
		return 0;
	
	if (!isset($data['plugins']))
		return 1;
	
	$attributes = array('name', 'description', 'manual', 'requirement');
	
	foreach ($data['plugins'] as $pluginName => &$plugin)
	{
		foreach ($attributes as $attribute)
		{
			if (isset($plugin[$attribute][$lang]))
				$plugin[$attribute] = $plugin[$attribute][$lang];
			else
				$plugin[$attribute] = current($plugin[$attribute]);
		}
		
		if (isset($plugin['versions'][$plugin['latestVersion']]['changelog'][$lang]))
			$plugin['versions'][$plugin['latestVersion']]['changelog'] = $plugin['versions'][$plugin['latestVersion']]['changelog'][$lang];
		else
			$plugin['versions'][$plugin['latestVersion']]['changelog'] = current($plugin['versions'][$plugin['latestVersion']]['changelog'][$lang]);
	}
	
	return $data['plugins'];
}

function checkPluginUpdate($plugins = NULL, $onlinePlugins = NULL)
{
	$plugins = ($plugins != NULL) ? $plugins : pluginList();
	$onlinePlugins = ($onlinePlugins != NULL) ? $onlinePlugins : getOnlinePlugins();
	
	if (!is_array($plugins) || !is_array($onlinePlugins))
		return array();
	
	$availableUpdates = array();
	
	foreach ($plugins as $plugin)
	{
		if (!isset($onlinePlugins[$plugin['id']]))
			continue;
		
		if ($plugin['version']['code'] < $onlinePlugins[$plugin['id']]['latestVersion'])
			$availableUpdates[$plugin['id']] = array('id' => $plugin['id'], 'name' => $plugin['name'], 'version' => array('name' => $plugin['version']['name'], 'code' => $onlinePlugins[$plugin['id']]['latestVersion']));
	}
	
	return $availableUpdates;
}
?>