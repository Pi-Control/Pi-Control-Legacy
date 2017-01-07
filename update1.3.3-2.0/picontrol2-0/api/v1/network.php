<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/rpi.function.php')						or die('Error: 0x0002');
(include_once LIBRARY_PATH.'main/tpl.class.php')						or die('Error: 0x0003');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0004');

$tpl = new PiTpl;
$tpl->setTpl($tpl);

$api = new API;

if (isset($_POST['execute']))
{
	do
	{
		if ($tpl->getSSHResource() === false)
		{
			$tpl->setError('error', 'logged out');
			break;
		}
		
		switch ($_POST['execute'])
		{
			case 'wlan':
				$networkConnections = getAllNetworkConnections();
				scanAccessPoints($networkConnections, true);
				$SSHReturn = $SSHError = '';
					break;
			default:
				$api->setError('error', 'Unknown execute.');
				break 2;
		}
		
		$api->addData('return', $SSHReturn);
		$api->addData('error', $SSHError);
	}
	while (false);
}
else
{
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

	$api->addData('networkConnections', $networkConnections);
	$api->addData('hostname', rpi_getHostname());
	$api->addData('wlan', scanAccessPoints($networkConnections, false));
}

$api->display();
?>