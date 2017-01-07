<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Einstellungen zu Plugins'));

$showList = true;

if (isset($_GET['status']) && $_GET['status'] != '' && ($gPlugin = pluginConfig($_GET['status'])) != false)
	include_once 'plugins_status.php';
elseif (isset($_GET['delete']) && $_GET['delete'] != '' && ($gPlugin = pluginConfig($_GET['delete'])) != false)
{
	$showList = false;
	include_once 'plugins_delete.php';
}

if ($showList == true)
{
	$plugins = pluginList();

	$tpl->assign('plugins', $plugins);

	$tpl->draw('settings/plugins');
}
?>