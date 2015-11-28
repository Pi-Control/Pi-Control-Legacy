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
	
	if ($pluginConfig['version']['require'] >= $config['versions']['versioncode'])
		$pluginConfig['compatible'] = true;
	else
		$pluginConfig['compatible'] = false;
	
	return $pluginConfig;
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
		
		if ($listConfig === true)
			$pluginList[$plugin] = $pluginConfig;
		else
			$pluginList[] = $plugin;
	}
	
	return $pluginList;
}
?>