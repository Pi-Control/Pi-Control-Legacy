<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')						or die('Error: 0x0001');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0002');

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
			case 'shutdown':
				list ($SSHReturn, $SSHError) = $tpl->executeSSH('sudo /sbin/shutdown -h now', true, 0);
					break;
			case 'restart':
				list ($SSHReturn, $SSHError) = $tpl->executeSSH('sudo /sbin/shutdown -r now', true, 0);
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

$api->display();
?>