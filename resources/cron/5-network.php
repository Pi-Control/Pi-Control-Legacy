<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php') 	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')	or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Error: 0x0002');
(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Error: 0x0003');

$networkConnections = getAllNetworkConnections();
$networkCountsJson = htmlspecialchars_decode(getConfig('main:network.overflowCount', '{}'));

$networkCounts = json_decode($networkCountsJson, true);

foreach ($networkConnections as $network)
{
	$log = new LogStatistic();
	$log->setFile(LOG_PATH.'statistic/network_'.$network['interface'].'.csv');
	$log->setLimit(2016);
	
	$last = $log->getLast();
	
	$countSent = 0;
	$countReceive = 0;
	
	if (isset($networkCounts[$network['interface']]['sent']))
		$countSent = $networkCounts[$network['interface']]['sent'];
		
	if (isset($networkCounts[$network['interface']]['receive']))
		$countReceive = $networkCounts[$network['interface']]['receive'];
	
	$rpiRuntime = rpi_getRuntime();
	
	if ((time() - $rpiRuntime) < (int) $last[0] && (float) $last[1] > (float) ($network['sent'] + 4294967295 * $countSent))
		$countSent++;
	
	if ((time() - $rpiRuntime) < (int) $last[0] && (float) $last[2] > (float) ($network['receive'] + 4294967295 * $countReceive))
		$countReceive++;
	
	if ((time() - $rpiRuntime) > (int) $last[0] && (int) $last[0] != 0)
	{
		$countSent = 0;
		$countReceive = 0;
	}
	
	$networkCounts[$network['interface']]['sent'] = $countSent;
	$networkCounts[$network['interface']]['receive'] = $countReceive;
	
	$log->add(array(time(), ($last[1] + (4294967295 * $countSent - $last[1]) + $network['sent']), ($last[2] + (4294967295 * $countReceive - $last[2]) + $network['receive'])));
	$log->close();
}
setConfig('main:network.overflowCount', htmlspecialchars(json_encode($networkCounts)));
?>