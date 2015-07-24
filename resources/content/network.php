<?php
$tpl = new RainTPL;

if (isset($_GET['hostname']))
{
	if ($_GET['hostname'] == 'save')
	{
		if (isset($_POST['hostname'], $_POST['submit']) && trim($_POST['hostname']) != '')
		{
			if (preg_match('/^([A-Za-z0-9]?[\-]?[A-Za-z0-9]?){1,63}$/', $_POST['hostname']))
			{
				include_once LIBRARY_PATH.'/main/ssh_connection.php';
				(include_once LIBRARY_PATH.'/network/network_functions.php');
				
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
	if (isset($_GET['refresh_wlan']) && !empty($_GET['refresh_wlan']))
		include_once LIBRARY_PATH.'/main/ssh_connection.php';
	
	$networkConnections = getAllNetworkConnections();
	$networkCountsJson = getConfigValue('config_network_count');
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