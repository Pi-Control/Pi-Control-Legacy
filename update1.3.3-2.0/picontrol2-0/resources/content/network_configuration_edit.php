<?php
if (!defined('PICONTROL')) exit();

$interfaceName = urldecode($_GET['edit']);
$interface = $networkInterface->getInterface($interfaceName);

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (isset($_POST['interface'], $_POST['protocol'], $_POST['method'], $_POST['checksum']) && ($pInterface = trim($_POST['interface'])) != '' && ($pProtocol = trim($_POST['protocol'])) != '' && ($pMethod = trim($_POST['method'])) != '' && ($pChecksum = trim($_POST['checksum'])) != '' && in_array($_POST['protocol'], array('inet', 'inet6', 'ipx')) && in_array($_POST['method'], array('dhcp', 'static', 'manual')))
	{
		if ($pChecksum == $networkInterface->getInterfaceHash())
		{
			$newInterfaceName = NULL;
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
			else
				$newInterface['iface'] = NULL;
			
			if ($pInterface != $interfaceName && $networkInterface->existsInterface($pInterface) == false)
				$newInterfaceName = $pInterface;
			elseif ($pInterface != $interfaceName)
				$tpl->msg('error', _t('Fehler'), _t('Leider konnte das Interface nicht gespeichert werden. Der Name f&uuml;r dieses Interface ist bereits vergeben.'), true, 10);
			
			if ($tpl->msgExists(10) === false)
			{
				if ($networkInterface->editInterface($interfaceName, $newInterface, $newInterfaceName) === true)
					$tpl->msg('success', _t('Interface gespeichert'), _t('Interface wurde erfolgreich gespeichert. Damit diese Einstellungen jedoch wirksam werden, muss das Interface neu gestartet werden.'));
				else
					$tpl->msg('error', _t('Fehler'), _t('Leider konnte das Interface nicht gespeichert werden. Es ist w&auml;hrend der &Uuml;bertragung ein Fehler aufgetreten.'));
					
				$interfaceName = $pInterface;
			}
		}
		else
			$tpl->msg('error', _t('Fehler'), _t('Leider wurde die Konfigurationsdatei zwischenzeitlich ver&auml;ndert, versuche es deshalb noch einmal.'));
	}
	else
		$tpl->msg('error', _t('Fehler'), _t('Bitte vergebe eine Interfacebezeichnung, ein Protokoll und eine Methode!'));
}

$interfaces = $networkInterface->getInterfaces();

$tpl->assign('checksum', $networkInterface->getInterfaceHash());
$tpl->assign('interfaceName', $interfaceName);
$tpl->assign('interfaceProtocol', isset($networkInterface->getInterface($interfaceName)['protocol']) ? $networkInterface->getInterface($interfaceName)['protocol'] : '');
$tpl->assign('interfaceMethod', isset($networkInterface->getInterface($interfaceName)['method']) ? $networkInterface->getInterface($interfaceName)['method'] : '');
$tpl->assign('interfaceAddress', isset($networkInterface->getInterface($interfaceName)['iface']['address']) ? $networkInterface->getInterface($interfaceName)['iface']['address'] : '');
$tpl->assign('interfaceNetmask', isset($networkInterface->getInterface($interfaceName)['iface']['netmask']) ? $networkInterface->getInterface($interfaceName)['iface']['netmask'] : '');
$tpl->assign('interfaceGateway', isset($networkInterface->getInterface($interfaceName)['iface']['gateway']) ? $networkInterface->getInterface($interfaceName)['iface']['gateway'] : '');

$tpl->draw('network_configuration_edit');
?>