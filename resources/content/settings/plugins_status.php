<?php
if (!defined('PICONTROL')) exit();

$plugin = $gPlugin;

if ($plugin['disabled'] == true)
{
	if (setPluginStatus($plugin['id'], true) == true)
		$tpl->msg('success', '', 'Das Plugin "'.$plugin['name'].'" wurde erfolgreich aktiviert.');
	else
		$tpl->msg('error', '', 'Das Plugin "'.$plugin['name'].'" konnte nicht aktiviert werden.');
}
elseif ($plugin['disabled'] == false)
{
	if (setPluginStatus($plugin['id'], false) == true)
		$tpl->msg('success', '', 'Das Plugin "'.$plugin['name'].'" wurde erfolgreich deaktiviert.');
	else
		$tpl->msg('error', '', 'Das Plugin "'.$plugin['name'].'" konnte nicht deaktiviert werden.');
}
?>