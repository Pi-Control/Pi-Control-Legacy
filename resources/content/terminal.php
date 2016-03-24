<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle('Terminal');

$selectedPort = (isset($_GET['port'])) ? $_GET['port'] : 9001;
$ports = array();

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
		if (($terminal = getConfig('terminal:port_'.$port['port'], '')) != '')
		{
			if (shell_exec('netstat -atn | grep :'.$port['port']) == '')
			{
				removeConfig('terminal:port_'.$port['port']);
				exec('kill -9 '.$terminal['pid']);
			}
		}
		
		if (isset($termials['port_'.$port['port']]) && shell_exec('netstat -atn | grep :'.$port['port']) != '')
			$ports[$index]['active'] = true;
	}
}

$tpl->assign('port', $selectedPort);
$tpl->assign('ports', $ports);
$tpl->assign('cookie', $_COOKIE['_pi-control_login']);

$tpl->draw('terminal');
?>