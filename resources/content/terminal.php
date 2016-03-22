<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle('Terminal');

$selectedPort = (isset($_GET['port'])) ? $_GET['port'] : 9001;
$ports = array();

$ipAddresses = json_decode(htmlspecialchars_decode(getConfig('terminal:filter.addresses', '{}')), true);
$ipAddressCheckEnabled = (getConfig('terminal:filter.enabled', 'false') == 'true') ? true : false;

if ($tpl->getSSHResource(1) !== false && ($ipAddressCheckEnabled == false || ($ipAddressCheckEnabled == true && ipInRange($_SERVER['REMOTE_ADDR'], $ipAddresses) == false)))
{
	$termials = getConfig('terminal', array());
	
	$ports = array(
					array('port' => 9001, 'active' => false),
					array('port' => 9002, 'active' => false),
					array('port' => 9003, 'active' => false),
					array('port' => 9004, 'active' => false),
					array('port' => 9005, 'active' => false)
				);
	
	foreach ($ports as $index => $port)
	{
		if (($terminal = getConfig('terminal:port_'.$port['port'], '')) != '')
		{
			if (shell_exec('netstat -atn | grep :'.$port['port']) == '')
			{
				removeConfig('terminal:port_'.$port['port']);
				exec('kill -9 '.$terminal['pid']);
			}
		}
		
		if (isset($termials['port_'.$port['port']]))
			$ports[$index]['active'] = true;
	}
}
elseif ($ipAddressCheckEnabled == true && ipInRange($_SERVER['REMOTE_ADDR'], $ipAddresses) == false)
	$tpl->error('Keine Berechtigung', '', true);

$tpl->assign('port', $selectedPort);
$tpl->assign('ports', $ports);

$tpl->draw('terminal');
?>