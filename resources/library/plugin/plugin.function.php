<?php
function pluginDisabled($pluginId)
{
	if (file_exists(PLUGINS_PATH.'/'.$pluginId.'/plugin_disabled.php') && is_file(PLUGINS_PATH.'/'.$pluginId.'/plugin_disabled.php'))
		return true;
	
	return false;
}

function pluginConfig($pluginId)
{
	global $config;
	
	if (!file_exists(PLUGINS_PATH.'/'.$pluginId.'/plugin.config.php') || !is_file(PLUGINS_PATH.'/'.$pluginId.'/plugin.config.php') || !include PLUGINS_PATH.'/'.$pluginId.'/plugin.config.php')
		return false;
	
	if (!isset($pluginConfig) || empty($pluginConfig))
		return false;
	
	if ($pluginConfig['id'] != $pluginId)
		return false;
	
	$pluginConfig['disabled'] = pluginDisabled($pluginId);
	
	if (file_exists(PLUGINS_PATH.'/'.$pluginId.'/resources/content/settings/settings.php') && is_file(PLUGINS_PATH.'/'.$pluginId.'/resources/content/settings/settings.php'))
		$pluginConfig['settings'] = true;
	else
		$pluginConfig['settings'] = false;
	
	if (file_exists(PLUGINS_PATH.'/'.$pluginId.'/resources/content/widget/widget.php') && is_file(PLUGINS_PATH.'/'.$pluginId.'/resources/content/widget/widget.php'))
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
	$langFile = PLUGINS_PATH.'/'.$pluginConfig['id'].'/resources/languages/'.$lang.'.php';
	
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
		
		if (!file_exists(PLUGINS_PATH.'/'.$plugin.'/plugin.config.php') || !is_file(PLUGINS_PATH.'/'.$plugin.'/plugin.config.php'))
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
?>