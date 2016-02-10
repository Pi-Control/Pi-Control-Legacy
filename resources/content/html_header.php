<?php
if (!defined('PICONTROL')) exit();

$tpl = new PiTpl;
$tpl->assign('title', (isset($data['title']) && $data['title'] != '') ? $data['title'] : 'Pi Control');

$pluginHeaderNavi = array();
$pluginHeaderNaviString = '';

if (file_exists(PLUGINS_PATH) && is_dir(PLUGINS_PATH))
{
	foreach (pluginList(false) as $plugin)
	{
		if (is_array($plugin))
			$pluginHeaderNavi[] = array('name' => $plugin['name'], 'id' => $plugin['id']);
	}
	
	if (empty($pluginHeaderNavi))
		$pluginHeaderNaviString = '<strong class="red">'._t('Keine Plugins!').'</strong>';
	
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
		$picontrolUpdate = checkUpdate();
		
		if (!is_array($picontrolUpdate))
			$tpl->setConfig('cron:updateCheck.picontrol', time());
		else
			$tpl->setConfig('cron:updateCheck.picontrol', time()-86400);
	}
}
else
	$pluginHeaderNaviString = '<strong class="red">Pluginordner nicht gefunden!</strong>';

$referer = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

if ($referer != '')
	$referer = '&referer='.urlencode($referer);

$tpl->assign('config_slim_header', $tpl->getConfig('main:other.slim_header', 'true'));
$tpl->assign('javascript_time', time()+date('Z', time()));
$tpl->assign('javascript_req_url', urlencode($_SERVER['REQUEST_URI']));
$tpl->assign('navi_plugins', !empty($pluginHeaderNavi) ? array_sort($pluginHeaderNavi, 'name', SORT_ASC) : $pluginHeaderNaviString);
$tpl->assign('navi_plugins_updates', isset($update_plugins) ? $update_plugins : '');
$tpl->assign('updatePicontrol', isset($picontrolUpdate) ? $picontrolUpdate : '');
$tpl->assign('cron_execution_fault', ($tpl->getConfig('cron:execution.cron', 0)+140 < time()) ? true : false);
$tpl->assign('username', $tpl->getConfig('user:user_'.$tpl->getConfig('login:token_'.$_COOKIE['_pi-control_login'].'.username', '').'.username', ''));
$tpl->assign('referer', $referer);

$tpl->draw('html_header');
?>