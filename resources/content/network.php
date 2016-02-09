<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'main/rpi.function.php') or die($error_code['0x0003']);
$tpl->setHeaderTitle(_t('Netzwerk'));

if (isset($_GET['hostname']))
{
	if ($_GET['hostname'] == 'save')
	{
		if (isset($_POST['hostname'], $_POST['submit']) && trim($_POST['hostname']) != '')
		{
			if (preg_match('/^([A-Za-z0-9]?[\-]?[A-Za-z0-9]?){1,63}$/', $_POST['hostname']))
			{
				$ssh = $tpl->getSSHResource();
				(include_once LIBRARY_PATH.'network/network.function.php');
				
				if (($hostname_status = editHostname($ssh, $_POST['hostname'])) === 0)
					$tpl->msg('green', '', 'Damit die Änderung wirksam wird, muss dein Raspberry Pi neu gestartet werden. <a href="http://raspberrypi/rpi/?action=system_restart">Jetzt neustarten.</a>');
				else
					$tpl->msg('red', '', $error_code['0x0039'].$hostname_status);
			}
			else
				$tpl->msg('red', '', $error_code['2x0011']);
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
	
	$networkCountsJson = getConfig('main:network.overflowCount', '{}');
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
	$tpl->assign('wlan', scanAccessPoints($networkConnections, isset($ssh) ? $ssh : ''));
	
	$tpl->draw('network');
}
?>