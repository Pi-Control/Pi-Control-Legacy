<?php
if (!defined('PICONTROL')) exit();

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (isset($_POST['interface'], $_POST['protocol'], $_POST['method'], $_POST['checksum']) && ($pInterface = trim($_POST['interface'])) != '' && ($pProtocol = trim($_POST['protocol'])) != '' && ($pMethod = trim($_POST['method'])) != '' && ($pChecksum = trim($_POST['checksum'])) != '' && in_array($_POST['protocol'], array('inet', 'inet6', 'ipx')) && in_array($_POST['method'], array('dhcp', 'static', 'manual')))
	{
		if ($pChecksum == $networkInterface->getInterfaceHash())
		{
			$newInterface = array('protocol' => $pProtocol, 'method' => $pMethod);
			
			if ($pMethod == 'static')
			{
				if (isset($_POST['address']) && ($pAddress = trim($_POST['address'])) != '')
					$newInterface['iface']['address'] = $pAddress;
				else
					$newInterface['iface']['address'] = NULL;
				
				if (isset($_POST['netmask']) && ($pNetmask = trim($_POST['netmask'])) != '')
					$newInterface['iface']['netmask'] = $pNetmask;
				else
					$newInterface['iface']['netmask'] = NULL;
				
				if (isset($_POST['gateway']) && ($pGateway = trim($_POST['gateway'])) != '')
					$newInterface['iface']['gateway'] = $pGateway;
				else
					$newInterface['iface']['gateway'] = NULL;
			}
			
			if ($networkInterface->existsInterface($pInterface) === true)
				$tpl->msg('error', '', 'Leider konnte das Interface nicht gespeichert werden. Der Name f&uuml;r dieses Interface ist bereits vergeben.', true, 10);
			
			if ($tpl->msgExists(10) === false)
			{
				if ($networkInterface->addInterface($pInterface, $newInterface) === true)
					$tpl->redirect('?s=network_configuration&edit='.urlencode($pInterface).'&msg=add');
				else
					$tpl->msg('error', '', 'Leider konnte das Interface nicht gespeichert werden. Es ist w&auml;hrend der &Uuml;bertragung ein Fehler aufgetreten.');
			}
		}
		else
			$tpl->msg('error', '', 'Leider wurde die Konfigurationsdatei zwischenzeitlich ver&auml;ndert, versuche es deshalb noch einmal.');
	}
	else
		$tpl->msg('error', '', 'Bitte vergebe eine Interfacebezeichnung, ein Protokoll und eine Methode!');
}

$tpl->assign('checksum', $networkInterface->getInterfaceHash());
$tpl->assign('interfaceName', isset($_POST['interface']) ? $_POST['interface'] : '');
$tpl->assign('interfaceProtocol', isset($_POST['protocol']) ? $_POST['protocol'] : '');
$tpl->assign('interfaceMethod', isset($_POST['method']) ? $_POST['method'] : '');
$tpl->assign('interfaceAddress', isset($_POST['address']) ? $_POST['address'] : '');
$tpl->assign('interfaceNetmask', isset($_POST['netmask']) ? $_POST['netmask'] : '');
$tpl->assign('interfaceGateway', isset($_POST['gateway']) ? $_POST['gateway'] : '');

$tpl->draw('network_configuration_add');
?>