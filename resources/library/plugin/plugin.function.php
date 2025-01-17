<?php
if (!defined('PICONTROL')) exit();

function initPluginConstants($pluginId = NULL)
{
	if ($pluginId == NULL)
	{
		$debug = debug_backtrace();
		$file = $debug[0]['file'];
		
		$file = str_replace(PLUGINS_PATH, '', $file);
		$explodes = explode('/', $file);
		
		$pluginId = $explodes[0];
	}
	
	defined('PLUGIN_ID') or define('PLUGIN_ID', $pluginId);
	defined('PLUGIN_PATH') or define('PLUGIN_PATH', PLUGINS_PATH.$pluginId.'/');
	
	return true;
}

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
	
	if ($pluginConfig['version']['require'] <= $config['version']['versioncode'])
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
		
		$pluginConfig['name'] = _t($pluginConfig['name']);
		$pluginConfig['description'] = _t($pluginConfig['description']);
		
		if ($listConfig === true)
			$pluginList[$pluginConfig['id']] = $pluginConfig;
		else
			$pluginList[] = $plugin;
	}
	
	array_multisort($pluginList, SORT_ASC);
	
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
	
	if (($stream = fopen(PLUGINS_PATH.$pluginId.'/plugin_disabled.php', 'w')) !== false)
	{
		if ((fwrite($stream, '<?php exit(); ?>')) !== false)
		{
			fclose($stream);
			return true;
		}
		else
		{
			fclose($stream);
			return false;
		}
	}
	else
		return false;
}

function deletePlugin($pluginId, $referer = NULL)
{
	if (empty($pluginId))
		return false;
	
	foreach (scandir(CRON_PATH) as $file)
	{
		if ($file[0] == '.' || !is_file(CRON_PATH.$file) || CRON_PATH.$file == 'init.php')
			continue;
		
		if (($pos = strpos($file, '-')) === false)
			continue;
		
		if (substr($file, $pos+1, strlen($pluginId)+8) == 'plugin.'.$pluginId.'.')
			unlink(CRON_PATH.$file);
	}
	
	foreach (scandir(CONFIG_PATH) as $file)
	{
		if ($file[0] == '.' || !is_file(CONFIG_PATH.$file))
			continue;
		
		if (substr($file, 0, strlen($pluginId)+8) == 'plugin.'.$pluginId.'.')
			unlink(CONFIG_PATH.$file);
	}
	
	foreach (scandir(LOG_PATH.'plugin/') as $file)
	{
		if ($file[0] == '.' || !is_file(LOG_PATH.'plugin/'.$file))
			continue;
		
		if (substr($file, 0, strlen($pluginId)+1) == $pluginId.'.')
			unlink(LOG_PATH.'plugin/'.$file);
	}
	
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
	
	$attributes = array('name', 'description', 'manual', 'requirement', 'screenshots');
	
	foreach ($data['plugins'] as $pluginName => &$plugin)
	{
		foreach ($attributes as $attribute)
		{
			if (!isset($plugin[$attribute]))
				continue;
			
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
	
	array_multisort($data['plugins'], SORT_ASC);
	
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

function getPluginConfig($config, $default = NULL)
{
	return getConfig('plugin.'.PLUGIN_ID.'.'.$config, $default);
}

function setPluginConfig($config, $value)
{
	return setConfig('plugin.'.PLUGIN_ID.'.'.$config, $value);
}

function removePluginConfig($config)
{
	return removeConfig('plugin.'.PLUGIN_ID.'.'.$config);
}
?>