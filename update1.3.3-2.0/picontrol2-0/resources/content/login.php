<?php
if (!defined('PICONTROL')) exit();

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Error: 0x0010');
(include_once LIBRARY_PATH.'main/tpl.class.php')			or die('Error: 0x0011');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Error: 0x0012');

$tpl = new PiTpl;
$tpl->setTpl($tpl);
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(false);
$tpl->setDrawFooter(false, $config);

$externalAccess = (urlIsPublic($_SERVER['REMOTE_ADDR']) && getConfig('main:access.external', 'false') == 'false') ? false : true;
$nextTry = getConfig('login:login.nextTry');

if ($externalAccess === false)
	$tpl->assign('errorMsg', _t('Der Zugang steht nur im lokalem Netzwerk (LAN) zur Verf&uuml;gung!'));
elseif ($nextTry > time())
	$tpl->assign('errorMsg', _t('Login noch f&uuml;r %d Sekunden gesperrt!', $nextTry-time()));

if (isset($_POST['submit'], $_POST['username'], $_POST['password']) && $externalAccess === true)
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
			if (setConfig('login:token_'.$uniqid.'.username', $pUsername)				!== true) break;
			if (setConfig('login:token_'.$uniqid.'.address', $_SERVER['REMOTE_ADDR'])	!== true) break;
			if (setConfig('user:user_'.$pUsername.'.last_login', time())				!== true) break;
			
			if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'checked')
			{
				setConfig('login:token_'.$uniqid.'.remember_me', 'true');
				setcookie('_pi-control_login', $uniqid, time()+60*60*24*30);
			}
			else
				setcookie('_pi-control_login', $uniqid, time()+60*60*12);
			
			if (isset($_POST['referer']) && $_POST['referer'] != '')
				header('Location: ?'.urldecode($_POST['referer']));
			else
				header('Location: ?s=overview');
			
			exit();
		}
		while (false);
		
		if ($nextTry > time())
		{
			$tpl->assign('errorMsg', _t('Fehler bei der Anmeldung!<br />Login noch f&uuml;r %d Sekunden gesperrt!', $nextTry-time()));
		}
		elseif ($try == 5)
		{
			$tpl->assign('errorMsg', _t('Fehler bei der Anmeldung!<br />Zu viele Fehlversuche. Login f&uuml;r %d Sekunden gesperrt!', 30));
			setConfig('login:login.nextTry', time() + 30);
		}
		elseif ($try == 6)
		{
			$tpl->assign('errorMsg', _t('Fehler bei der Anmeldung!<br />Zu viele Fehlversuche. Login f&uuml;r %d Minute gesperrt!', 1));
			setConfig('login:login.nextTry', time() + 60);
		}
		elseif ($try == 7)
		{
			$tpl->assign('errorMsg', _t('Fehler bei der Anmeldung!<br />Zu viele Fehlversuche. Login f&uuml;r %d Minuten gesperrt!', 2));
			setConfig('login:login.nextTry', time() + 120);
		}
		elseif ($try >= 8)
		{
			$tpl->assign('errorMsg', _t('Fehler bei der Anmeldung!<br />Zu viele Fehlversuche. Login f&uuml;r %d Minuten gesperrt!', 5));
			setConfig('login:login.nextTry', time() + 300);
		}
		else
		{
			$tpl->assign('errorMsg', _t('Fehler bei der Anmeldung!'));
		}
		
		setConfig('login:login.try', $try + 1);
	}
}

if (isset($_GET['logout']))
{
	if (isset($_COOKIE['_pi-control_login']))
	{
		$uniqid = $_COOKIE['_pi-control_login'];
		removeConfig('login:token_'.$uniqid);
		setcookie('_pi-control_login', '', time()-60);
	}
	
	if (isset($_GET['referer']) && $_GET['referer'] != '')
		header('Location: ?i=login&referer='.urlencode($_GET['referer']));
	else
		header('Location: ?i=login');
	
	exit();
}

$tpl->assign('referer', isset($_GET['referer']) ? $_GET['referer'] : (isset($_POST['referer']) ? urlencode($_POST['referer']) : ''));
$tpl->assign('externalAccess', $externalAccess);

$tpl->draw('login');
?>