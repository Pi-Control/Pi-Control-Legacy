<?php
$tpl = new PiTpl;
$tpl->assign('title', (isset($data['title']) && $data['title'] != '') ? $data['title'] : 'Pi Control');

if (file_exists(PLUGINS_PATH) && is_dir(PLUGINS_PATH))
{
	$plugin_available = array();
	
	/*foreach (getPlugins() as $plugin)
	{
		$plugin_information = NULL;
		$plugin_information = getPluginInfo($plugin);
		
		if (is_array($plugin_information))
			$plugin_available['all'][] = array('name' => $plugin_information['name'], 'id' => $plugin_information['id']);
			if ($plugin_information['status'] == 'enable')
				$plugin_available['status'][] = array('name' => $plugin_information['name'], 'id' => $plugin_information['id']);
	}*/
	
	if (!isset($plugin_available['status']))
		$plugin_available_string = '<strong class="red">'._t('Keine Plugins!').'</strong>';
	
	if (($tpl->getConfig('cron:updateCheck.plugins', 0)+86400) < time() || (isset($_GET['s']) && $_GET['s'] == 'plugin_search'))
	{
		// isset($update_plugins) kann wieder weg !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		///$update_plugins = checkPluginUpdate(isset($plugin_available['all']) ? $plugin_available['all'] : '');
		if (isset($update_plugins) && !is_array($update_plugins))
			$tpl->setConfig('cron:updateCheck.plugins', time());
		else
			$tpl->setConfig('cron:updateCheck.plugins', time()-86400);
	}
	
	if (($tpl->getConfig('cron:updateCheck.picontrol', 0)+86400) < time() || (isset($_GET['s'], $_GET['do']) && $_GET['s'] == 'settings' && $_GET['do'] == 'update'))
	{
		$picontrol_update = checkUpdate();
		if (!is_array($picontrol_update))
			$tpl->setConfig('cron:updateCheck.picontrol', time());
		else
			$tpl->setConfig('cron:updateCheck.picontrol', time()-86400);
	}
}
else
	$plugin_available_string = '<strong class="red">Pluginordner nicht gefunden!</strong>';

$tpl->assign('config_slim_header', $tpl->getConfig('main:other.slim_header', 'true'));
$tpl->assign('javascript_time', time()+date('Z', time()));
$tpl->assign('javascript_req_url', urlencode($_SERVER['REQUEST_URI']));
$tpl->assign('navi_plugin_available', isset($plugin_available['status']) ? array_sort($plugin_available['status'], 'name', SORT_ASC) : $plugin_available_string);
$tpl->assign('navi_plugin_updates', isset($update_plugins) ? $update_plugins : '');
$tpl->assign('update_picontrol', isset($picontrol_update) ? $picontrol_update : '');
$tpl->assign('last_cron_execution', $tpl->getConfig('cron:execution.cron', 1448999193)+140); // TODO Entfernen des Default-Wertes (0)

$tpl->draw('html_header');
?>