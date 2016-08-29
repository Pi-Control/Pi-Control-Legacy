<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/rpi.function.php')						or die('Error: 0x0002');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0003');

$api = new API;

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

$api->display();
?>