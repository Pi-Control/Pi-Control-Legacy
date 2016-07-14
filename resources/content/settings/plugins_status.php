<?php
if (!defined('PICONTROL')) exit();

$plugin = $gPlugin;

if ($plugin['disabled'] == true)
{
	if (setPluginStatus($plugin['id'], true) == true)
		$tpl->msg('success', _t('Plugin aktiviert'), _t('Das Plugin "%s" wurde erfolgreich aktiviert.', $plugin['name']));
	else
		$tpl->msg('error', _t('Fehler'), _t('Das Plugin "%s" konnte nicht aktiviert werden.', $plugin['name']));
}
elseif ($plugin['disabled'] == false)
{
	if (setPluginStatus($plugin['id'], false) == true)
		$tpl->msg('success', _t('Plugin deaktiviert'), _t('Das Plugin "%s" wurde erfolgreich deaktiviert.', $plugin['name']));
	else
		$tpl->msg('error', _t('Fehler'), _t('Das Plugin "%s" konnte nicht deaktiviert werden.', $plugin['name']));
}
?>