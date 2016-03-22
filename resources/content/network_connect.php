<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('WLAN-Verbindung herstellen'));

$tpl->assign('jsVariables', 'var _interface = \''.urldecode($_GET['interface']).'\'; var _ssid = \''.urldecode($_GET['ssid']).'\';');
$tpl->assign('interface', urldecode($_GET['interface']));
$tpl->assign('ssid', urldecode($_GET['ssid']));
$tpl->assign('encryption', urldecode($_GET['encryption']));

$tpl->draw('network_connect');
?>