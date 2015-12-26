<?php
$tpl->setHeaderTitle(_t('Einstellungen - &Uuml;bersicht'));

if (isset($_POST['submit-main']) && $_POST['submit-main'] != '')
{
	if (isset($_POST['theme-color']) && in_array($_POST['theme-color'], array('red', 'pink', 'purple', 'deepPurple', 'indigo', 'blue', 'lightBlue', 'cyan', 'teal', 'green', 'lightGreen', 'lime', 'yellow', 'amber', 'orange', 'deepOrange', 'brown', 'grey', 'blueGrey')) === true)
	{
		$tpl->setConfig('main:theme.color', $_POST['theme-color']);
		$tpl->setConfig('main:theme.colorChanged', time());
		$tpl->msg('success', '', 'Die Einstellungen wurden erfolgreich gespeichert.', true, 10);
	}
	
	if (isset($_POST['external-access']) && $_POST['external-access'] == 'checked')
	{
		$tpl->setConfig('main:access.external', 'true');
		$tpl->msg('success', '', 'Die Einstellungen wurden erfolgreich gespeichert.', true, 10);
	}
	else
	{
		$tpl->setConfig('main:access.external', 'false');
		$tpl->msg('success', '', 'Die Einstellungen wurden erfolgreich gespeichert.', true, 10);
	}
}

$tpl->assign('main-show-devices', getConfig('main:overview.showDevices', 'true'));

$tpl->draw('settings/overview');
?>