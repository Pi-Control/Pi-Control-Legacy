<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/tpl.class.php')						or die('Error: 0x0002');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0003');

$tpl = new PiTpl;
$tpl->setTpl($tpl);

$api = new API;

if (isset($_POST['login']))
{
	$pType = isset($_POST['type']) ? $_POST['type'] : '';
	
	if ($pType == 'password')
	{
		if (isset($_POST['port'], $_POST['username'], $_POST['password']) && ($pPort = intval(trim($_POST['port']))) != '' && ($pUsername = trim($_POST['username'])) != '' && ($pPassword = $_POST['password']) != '')
		{
			$pRememberMe = (isset($_POST['remember-me']) && $_POST['remember-me'] == 'checked') ? true : false;
			
			if (is_numeric($pPort) && $pPort >= 0 && $pPort <= 65535)
			{
				if ($tpl->setSSHInfo($pType, $pPort, $pUsername, $pPassword, NULL, $pRememberMe) === true)
				{
					if ($tpl->getSSHResource() !== false)
						$api->addData('success', 'login');
					else
						$api->setError('error', 'login');
				}
			}
			else
				$api->setError('error', 'Invalid port.');
		}
		else
			$api->setError('error', 'Missing parameters.');
	}
	elseif ($pType == 'publickey')
	{
		if (isset($_POST['port'], $_POST['username'], $_POST['privatekey']) && ($pPort = intval(trim($_POST['port']))) != '' && ($pUsername = trim($_POST['username'])) != '' && ($pPrivateKey = urldecode($_POST['privatekey'])) != '')
		{
			$pPassword = isset($_POST['password']) ? $_POST['password'] : '';
			$pRememberMe = (isset($_POST['remember-me']) && $_POST['remember-me'] == 'checked') ? true : false;
			
			if (is_numeric($pPort) && $pPort >= 0 && $pPort <= 65535)
			{
				if ($tpl->setSSHInfo($pType, $pPort, $pUsername, $pPassword, $pPrivateKey, $pRememberMe) === true)
				{
					if ($tpl->getSSHResource() !== false)
						$api->addData('success', 'login');
					else
						$api->setError('error', 'login');
				}
			}
			else
				$api->setError('error', 'Invalid port.');
		}
		else
			$api->setError('error', 'Missing parameters.');
	}
	else
		$api->setError('error', 'Unknown type.');
}

if (isset($_POST['status']))
{
	if ($tpl->getSSHResource() !== false)
		$api->addData('status', 'logged in');
	else
	{
		$api->addData('status', 'logged out');
		$api->addData('ssh', $tpl->getSSHInfo());
	}
}

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
				list ($SSHReturn, $SSHError, $SSHExitStatus) = $tpl->executeSSH('sudo shutdown -h now', true, 0);
					break;
			case 'restart':
				list ($SSHReturn, $SSHError, $SSHExitStatus) = $tpl->executeSSH('sudo shutdown -r now', true, 0);
					break;
			default:
				$api->setError('error', 'Unknown execute.');
				break 2;
		}
		
		$api->addData('return', $SSHReturn);
		$api->addData('error', $SSHError);
		$api->addData('exitStatus', $SSHExitStatus);
	}
	while (false);
}

if (isset($_POST['logout']))
{
	$tpl->logoutSSH();
}

$api->display();
?>