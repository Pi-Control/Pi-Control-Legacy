<?php
if (isset($_POST['submit']))
{
	$tpl->msg('success', 'Konfiguration', 'Die Daten wurden erfolgreich gespeichert.', true, 1);
	
	if (isset($_POST['checkbox']) && $_POST['checkbox'] == 'checked')
		$tpl->setConfig('main:test.checkbox', true) or $tpl->msg('error', 'Konfiguration', 'Fehler beim Speichern der Daten.', true, 1);
	else
		$tpl->setConfig('main:test.checkbox', false) or $tpl->msg('error', 'Konfiguration', 'Fehler beim Speichern der Daten.', true, 1);
	
	if (isset($_POST['text']) && ($pText = trim($_POST['text'])) != '')
		$tpl->setConfig('main:test.text', $pText) or $tpl->msg('error', 'Konfiguration', 'Fehler beim Speichern der Daten.', true, 1);
}

$tpl->assign('configCheckbox', $tpl->getConfig('main:test.checkbox', false));
$tpl->assign('configText', $tpl->getConfig('main:test.text', ''));

$tpl->draw('settings/settings');
?>