<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Einstellungen zum Terminal'));

$ipAddressCheck = $_SERVER['REMOTE_ADDR'];

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (!isset($_POST['enable']) || $_POST['enable'] != 'checked')
		setConfig('terminal:filter.enabled', 'false');
	
	if (isset($_POST['ip-addresses']) && ($pIpAddresses = $_POST['ip-addresses']) != '')
	{
		if (isset($_POST['enable']) && $_POST['enable'] == 'checked')
			setConfig('terminal:filter.enabled', 'true');
		
		$explode = preg_split('/\r\n|\r|\n/', $pIpAddresses);
		
		setConfig('terminal:filter.addresses', htmlspecialchars(json_encode($explode)));
	}
	else
		$tpl->msg('error', '', 'Bitte f&uuml;lle das Feld "IP-Adressen zulassen" aus!');
	
	if (isset($_POST['ip-address-check']) && ($pIpAddressCheck = trim($_POST['ip-address-check'])) != '' && filter_var($_POST['ip-address-check'], FILTER_VALIDATE_IP))
		$ipAddressCheck = $pIpAddressCheck;
}

$ipAddresses = json_decode(htmlspecialchars_decode(getConfig('terminal:filter.addresses', '{}')), true);

$tpl->assign('terminalEnabled', (getConfig('terminal:filter.enabled', 'false') == 'true') ? true : false);
$tpl->assign('ipAddresses', implode(PHP_EOL, $ipAddresses));
$tpl->assign('ipAddressCheck', $ipAddressCheck);
$tpl->assign('ipAddressCheckStatus', ipInRange($ipAddressCheck, $ipAddresses));

$tpl->draw('settings/terminal');
?>