<?php
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0002');

$api = new API;

$externalAccess = (urlIsPublic($_SERVER['REMOTE_ADDR']) && getConfig('main:access.external', 'false') == 'false') ? false : true;
$nextTry = getConfig('login:login.nextTry');

if ($externalAccess === false)
	$api->addData('error', 'Local network.');

if ($nextTry > time())
	$api->addData('error', array('msg' => 'Login disabled.', 'seconds' => $nextTry-time()));

if (isset($_POST['username'], $_POST['password']) && $externalAccess === true)
{
	if (trim($_POST['username']) != '' && $_POST['password'] != '')
	{
		$pUsername = strtolower(trim($_POST['username']));
		$pPassword = $_POST['password'];
		
		$try = getConfig('login:login.try');
		
		do
		{
			if ($nextTry > time())
				break;
			
			if (($userinfo = getConfig('user:user_'.$pUsername, 0)) === 0)
				break;
			
			if (!is_array($userinfo))
				break;
			
			if (strtolower($userinfo['username']) != $pUsername || password_verify($pPassword, $userinfo['password']) !== true)
				break;
			
			setConfig('login:login.try', 0);
			setConfig('login:login.nextTry', 0);
			$uniqid = generateUniqId(32, false);
			
			if (setConfig('login:token_'.$uniqid.'.created', time())					!== true) break;
			if (setConfig('login:token_'.$uniqid.'.username', $pUsername) 				!== true) break;
			if (setConfig('login:token_'.$uniqid.'.address', $_SERVER['REMOTE_ADDR']) 	!== true) break;
			if (setConfig('user:user_'.$pUsername.'.last_login', time())				!== true) break;
			
			if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'checked')
				setConfig('login:token_'.$uniqid.'.remember_me', 'true');
			
			$api->addData('token', $uniqid);
			
			$api->display();
			exit();
		}
		while (false);
		
		if ($nextTry > time())
		{
			$api->addData('error', array('msg' => 'Login disabled.', 'seconds' => $nextTry-time()));
		}
		elseif ($try == 5)
		{
			$api->addData('error', array('msg' => 'Login failed.', 'seconds' => 30));
			setConfig('login:login.nextTry', time() + 30);
		}
		elseif ($try == 6)
		{
			$api->addData('error', array('msg' => 'Login failed.', 'seconds' => 60));
			setConfig('login:login.nextTry', time() + 60);
		}
		elseif ($try == 7)
		{
			$api->addData('error', array('msg' => 'Login failed.', 'seconds' => 120));
			setConfig('login:login.nextTry', time() + 120);
		}
		elseif ($try >= 8)
		{
			$api->addData('error', array('msg' => 'Login failed.', 'seconds' => 300));
			setConfig('login:login.nextTry', time() + 300);
		}
		else
		{
			$api->addData('error', array('msg' => 'Login failed.'));
		}
		
		setConfig('login:login.try', $try + 1);
	}
}

if (isset($_POST['logout']))
{
	if (isset($_POST['token']))
	{
		$uniqid = $_POST['token'];
		removeConfig('login:token_'.$uniqid);
		
		$api->addData('success', 'logout');
	}
	else
		$api->setError('error', 'No token set.');
	
	$api->display();
	exit();
}

$api->display();
?>