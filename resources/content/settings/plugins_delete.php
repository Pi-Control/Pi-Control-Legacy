<?php
if (!defined('PICONTROL')) exit();

$plugin = $gPlugin;

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (deletePlugin($plugin['id']) == true)
	{
		$tpl->msg('success', '', 'Das Plugin "'.$plugin['name'].'" wurde erfolgreich gel&ouml;scht.');
		$showList = true;
	}
	else
		$tpl->msg('error', '', 'Das Plugin "'.$plugin['name'].'" konnte nicht gel&ouml;scht werden.');
}

if ($showList == false)
{
	$tpl->assign('plugin', $plugin);

	$tpl->draw('settings/plugin_delete');
}
?>