<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Error: 0x0010');
(include_once LIBRARY_PATH.'network/network.function.php')	or die('Error: 0x0011');
$tpl->setHeaderTitle(_t('Netzwerk'));

if (isset($_GET['hostname']))
{
	if (isset($_POST['submit']) && $_POST['submit'] != '')
	{
		if (isset($_POST['hostname']) && trim($pHostname = trim($_POST['hostname'])) != '')
		{
			if (preg_match('/^([a-z][a-z0-9\-\.]*[^\-]){1,24}$/im', $pHostname))
			{
				if (($status = editHostname($pHostname)) === true)
					$tpl->msg('success', _t('Hostname gespeichert'), _t('Damit die &Auml;nderung wirksam wird, muss dein Raspberry Pi neu gestartet werden. <a href="%s">Jetzt neu starten.</a>', '?s=shutdown&restart'));
				else
					$tpl->msg('error', _t('Fehler'), _t('Fehler beim &Auml;ndern des Hostname! Fehlercode: %s', $status));
			}
			else
				$tpl->msg('error', _t('Fehler'), _t('Der Hostname ist ung&uuml;ltig! Er muss aus mindestens 1 bis 24 Zeichen bestehen und darf nur folgende Zeichen enthalten: A-Z a-z 0-9 -<br />Der Hostname darf nicht mit einem Bindestrich Anfangen oder Enden.'));
		}
	}
	
	$tpl->assign('hostname', rpi_getHostname());
	
	$tpl->draw('network_hostname');
}
else
{
	$ssh = NULL;
	
	if (isset($_GET['refresh_wlan']) && !empty($_GET['refresh_wlan']))
		$ssh = $tpl->getSSHResource();
	
	$networkConnections = getAllNetworkConnections();
	
	$networkCountsJson = htmlspecialchars_decode(getConfig('main:network.overflowCount', '{}'));
	$networkCounts = json_decode($networkCountsJson, true);
	$counter = 0;
	
	foreach ($networkConnections as $network)
	{
		$countSent = 0;
		$countReceive = 0;
		
		if (isset($networkCounts[$network['interface']]['sent']))
			$countSent = $networkCounts[$network['interface']]['sent'];
			
		if (isset($networkCounts[$network['interface']]['receive']))
			$countReceive = $networkCounts[$network['interface']]['receive'];
		
		$networkConnections[$counter]['sent'] = (4294967295 * $countSent) + $network['sent'];
		$networkConnections[$counter]['receive'] = (4294967295 * $countReceive) + $network['receive'];
		
		$counter += 1;
	}
	
	$tpl->assign('network_connections', $networkConnections);
	$tpl->assign('hostname', rpi_getHostname());
	$tpl->assign('wlan', scanAccessPoints($networkConnections, (isset($_GET['refresh_wlan'])) ? true : false));
	
	$tpl->draw('network');
}
?>