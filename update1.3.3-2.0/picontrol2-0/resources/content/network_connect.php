<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('WLAN-Verbindung herstellen'));

$jsTranslations = array();
$jsTranslations[] = 'Das Passwort sollte mindestens 8 Zeichen betragen.';
$jsTranslations[] = 'Verbindung wird getrennt...';
$jsTranslations[] = 'Verbindung wird wieder hergestellt...';
$jsTranslations[] = 'Ermittle IP-Adresse von Verbindung...';
$jsTranslations[] = 'Verbindung mit "%%s" war erfolgreich.';
$jsTranslations[] = 'IP-Adresse';

$tpl->assign('jsVariables', 'var _interface = \''.urldecode($_GET['interface']).'\'; var _ssid = \''.urldecode($_GET['ssid']).'\';');
$tpl->assign('interface', urldecode($_GET['interface']));
$tpl->assign('ssid', urldecode($_GET['ssid']));
$tpl->assign('encryption', urldecode($_GET['encryption']));
$tpl->assign('jsTranslations', $jsTranslations);

$tpl->draw('network_connect');
?>