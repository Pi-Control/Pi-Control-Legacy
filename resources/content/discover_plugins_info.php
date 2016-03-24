<?php
if (!defined('PICONTROL')) exit();

$pluginId = $_GET['id'];

$tpl->setHeaderTitle($onlinePlugins[$pluginId]['name'].' - '._t('Plugins entdecken'));

if (isset($_GET['status']) && $_GET['status'] == '')
{
	$gPlugin = $plugins[$pluginId];
	include_once 'settings/plugins_status.php';
	$plugins = pluginList();
}

if (isset($_GET['install']) && $_GET['install'] == '')
	$tpl->redirect('?i=download_plugin&id='.$pluginId);

if (isset($_GET['installed']) && $_GET['installed'] == '')
	$tpl->msg('success', '', 'Das Plugin wurde erfolgreich installiert.');

if (isset($_GET['update']) && $_GET['update'] == '')
	$tpl->redirect('?i=update_plugin&id='.$pluginId);

if (isset($_GET['updated']) && $_GET['updated'] == '')
	$tpl->msg('success', '', 'Das Plugin wurde erfolgreich aktualisiert.');

$tpl->assign('plugin', isset($plugins[$pluginId]) ? $plugins[$pluginId] : array());
$tpl->assign('onlinePlugin', $onlinePlugins[$pluginId]);

$tpl->draw('discover_plugins_info');
?>