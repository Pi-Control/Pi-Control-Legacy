<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle('Terminal');

$selectedPort = (isset($_GET['port'])) ? $_GET['port'] : 9001; //0

if ($tpl->getSSHResource(1) !== false)
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
		/*if (!isset($termials['port_'.$port['port']]) && $selectedPort == 0)
			$selectedPort = $port['port'];*/
		/*else*/if (isset($termials['port_'.$port['port']]))
			$ports[$index]['active'] = true;
	}
	
	/*if ($selectedPort == 0)
		$tpl->msg('error', 'Kein Port verf&uuml;gbar', 'Leider sind momentan alle f&uuml;nf verf&uuml;gbaren Ports belegt. Es kann somit kein neues Terminal erstellt werden.');
	else
	{
		
	}*/
}

$tpl->assign('port', $selectedPort);
$tpl->assign('ports', $ports);

$tpl->draw('terminal');
?>