<?php
if (!defined('PICONTROL')) exit();

$plugin = $gPlugin;

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (deletePlugin($plugin['id']) == true)
	{
		$tpl->msg('success', _t('Plugin gel&ouml;scht'), _t('Das Plugin "%s" wurde erfolgreich gel&ouml;scht.', $plugin['name']));
		$showList = true;
	}
	else
		$tpl->msg('error', _t('Fehler'), _t('Das Plugin "%s" konnte nicht gel&ouml;scht werden.', $plugin['name']));
}

if ($showList == false)
{
	$tpl->assign('plugin', $plugin);

	$tpl->draw('settings/plugin_delete');
}
?>