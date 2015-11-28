<?php
function pluginDisabled($pluginId)
{
	if (file_exists(PLUGINS_PATH.'/'.$pluginId.'/plugin_disabled.php') && is_file(PLUGINS_PATH.'/'.$pluginId.'/plugin_disabled.php'))
		return true;
	
	return false;
}

function pluginConfig($pluginId)
{
	if (!file_exists(PLUGINS_PATH.'/'.$pluginId.'/plugin.config.php') || !is_file(PLUGINS_PATH.'/'.$pluginId.'/plugin.config.php') || !include PLUGINS_PATH.'/'.$pluginId.'/plugin.config.php')
		return false;
	
	if (!isset($pluginConfig) || empty($pluginConfig))
		return false;
	
	$pluginConfig['disabled'] = pluginDisabled($pluginId);
	
	if (file_exists(PLUGINS_PATH.'/'.$pluginId.'/settings/settings.php') && is_file(PLUGINS_PATH.'/'.$pluginId.'/settings/settings.php'))
		$pluginConfig['settings'] = true;
	else
		$pluginConfig['settings'] = false;
	
	if (file_exists(PLUGINS_PATH.'/'.$pluginId.'/widget/widget.php') && is_file(PLUGINS_PATH.'/'.$pluginId.'/widget/widget.php'))
		$pluginConfig['widget'] = true;
	else
		$pluginConfig['widget'] = false;
	
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
		
		if ($listDisabled === false && pluginDisabled($plugin) === true)
			continue;
		
		if ($listConfig === true)
			$pluginList[] = pluginConfig($plugin);
		else
			$pluginList[] = $plugin;
	}
	
	return $pluginList;
}
?>