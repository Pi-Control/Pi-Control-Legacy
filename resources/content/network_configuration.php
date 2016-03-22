<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'network/network.function.php') or die('');
(include_once LIBRARY_PATH.'network/network.class.php') or die('');
$tpl->setHeaderTitle(_t('Netzwerkkonfiguration'));

$networkInterface = new NetworkInterface($tpl);

if (isset($_GET['msg']) && $_GET['msg'] == 'add')
	$tpl->msg('success', '', 'Interface wurde erfolgreich hinzugef&uuml;gt. Damit diese Einstellungen jedoch wirksam werden, muss das Interface neu gestartet werden.');
elseif (isset($_GET['msg']) && $_GET['msg'] == 'delete')
	$tpl->msg('success', '', 'Interface wurde erfolgreich gel&ouml;scht.');

if (isset($_GET['add']) && $_GET['add'] == '')
	include_once 'network_configuration_add.php';
elseif (isset($_GET['edit']) && $_GET['edit'] != '' && $networkInterface->existsInterface(urldecode($_GET['edit'])))
	include_once 'network_configuration_edit.php';
elseif (isset($_GET['delete']) && $_GET['delete'] != '' && $networkInterface->existsInterface(urldecode($_GET['delete'])))
	include_once 'network_configuration_delete.php';
elseif (isset($_GET['edit']) || isset($_GET['delete']))
{
	$tpl->msg('error', 'Interface nicht verf&uuml;gbar', 'Es wurde kein Interface mit dem angegebenen Namen gefunden!', false);
	
	$tpl->assign('interfaces', $networkInterface->getInterfaces());
	
	$tpl->draw('network_configuration');
}
else
{
	$tpl->assign('interfaces', $networkInterface->getInterfaces());

	$tpl->draw('network_configuration');
}
?>