<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')						or die('Error: 0x0001');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0002');

$tpl = new PiTpl;
$tpl->setTpl($tpl);

$api = new API;

if (isset($_POST['interface']) && ($pInterface = trim($_POST['interface'])) != '')
{
	list ($status, $error, $exitStatus) = $tpl->executeSSH('sudo ifdown '.escapeshellarg($pInterface).' && sudo ifup '.escapeshellarg($pInterface), 60);
	
	$api->addData('success', 'true');
	$api->addData('status', $status);
	$api->addData('error', $error);
	$api->addData('exitStatus', $exitStatus);
}
else
	$api->setError('error', 'No interface set.');

$api->display();
?>