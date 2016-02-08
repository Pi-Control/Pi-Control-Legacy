<?php
$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')			or die($error_code['0x0001']);
(include_once LIBRARY_PATH.'main/main.function.php')		or die($error_code['0x0002']);

$tpl = new PiTpl;
$tpl->setTpl($tpl);
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(false);
$tpl->setDrawFooter(false, $config, $errorHandler);

$externalAccess = (urlIsPublic($_SERVER['REMOTE_ADDR']) && getConfig('main:access.external', 'false') == 'false') ? false : true;

if ($externalAccess === false)
	$tpl->assign('errorMsg', 'Der Zugang steht nur im lokalem Netzwerk (LAN) zur Verf&uuml;gung!');
	
if (isset($_POST['submit'], $_POST['username'], $_POST['password']) && $externalAccess === true)
{
	$pUsername = strtolower(trim($_POST['username']));
	$pPassword = $_POST['password'];
	
	do
	{
		if (($userinfo = $tpl->getConfig('user:user_'.$pUsername, 0)) === 0)
			break;
		
		if (!is_array($userinfo))
			break;
		
		if (strtolower($userinfo['username']) != $pUsername || password_verify($pPassword, $userinfo['password']) !== true)
			break;
		
		$uniqid = generateUniqId(32, false);
		
		if ($tpl->setConfig('login:token_'.$uniqid.'.created', time())					!== true) break;
		if ($tpl->setConfig('login:token_'.$uniqid.'.username', $pUsername) 			!== true) break;
		if ($tpl->setConfig('login:token_'.$uniqid.'.address', $_SERVER['REMOTE_ADDR']) !== true) break;
		if ($tpl->setConfig('user:user_'.$pUsername.'.last_login', time())				!== true) break;
		
		if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'checked')
		{
			$tpl->setConfig('login:token_'.$uniqid.'.remember_me', 'true');
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
	
	$tpl->assign('errorMsg', 'Fehler bei der Anmeldung!');
}

if (isset($_GET['logout']))
{
	if (isset($_COOKIE['_pi-control_login']))
	{
		$uniqid = $_COOKIE['_pi-control_login'];
		removeConfig('login:token_'.$uniqid);
		setcookie('_pi-control_login', '', time()-60);
	}
	session_destroy();
	
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