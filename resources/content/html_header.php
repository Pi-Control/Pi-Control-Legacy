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
	
	if ((getConfig('cron:updateCheck.plugins', 0)+86400) < time() || (isset($_GET['s']) && $_GET['s'] == 'discover_plugins'))
	{
		$availableUpdates = checkPluginUpdate();
		
		if (isset($availableUpdates) && is_array($availableUpdates) && !empty($availableUpdates))
			setConfig('cron:updateCheck.plugins', time()-86400);
		else
			setConfig('cron:updateCheck.plugins', time());
	}
	
	if ((getConfig('cron:updateCheck.picontrol', 0)+86400) < time() || (isset($_GET['s'], $_GET['do']) && $_GET['s'] == 'settings' && $_GET['do'] == 'update'))
	{
		$picontrolUpdate = checkUpdate();
		
		if (!is_array($picontrolUpdate))
			setConfig('cron:updateCheck.picontrol', time());
		else
			setConfig('cron:updateCheck.picontrol', time()-86400);
	}
}
else
	$pluginHeaderNaviString = '<strong class="red">'._t('Pluginordner nicht gefunden!').'</strong>';

$referer = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

if ($referer != '')
	$referer = '&referer='.urlencode($referer);

$tpl->assign('naviPlugins', !empty($pluginHeaderNavi) ? array_sort($pluginHeaderNavi, 'name', SORT_ASC) : $pluginHeaderNaviString);
$tpl->assign('naviPluginsUpdates', (isset($availableUpdates) && !empty($availableUpdates)) ? $availableUpdates : NULL);
$tpl->assign('updatePicontrol', isset($picontrolUpdate) ? $picontrolUpdate : '');
$tpl->assign('cronExecutionFault', (getConfig('cron:execution.cron', 0)+140 < time()) ? true : false);
$tpl->assign('username', getConfig('user:user_'.getConfig('login:token_'.$_COOKIE['_pi-control_login'].'.username', '').'.username', ''));
$tpl->assign('referer', $referer);

$tpl->draw('html_header');
?>