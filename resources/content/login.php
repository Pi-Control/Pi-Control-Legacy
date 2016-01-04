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
	
	if (($userinfo = $tpl->getConfig('user:user_'.$pUsername, 0)) === 0)
		goto error;
	
	if (!is_array($userinfo))
		goto error;
	
	if (strtolower($userinfo['username']) != $pUsername || $userinfo['password'] != md5($pPassword))
		goto error;
	
	$uniqid = generateUniqId();
	
	if ($tpl->setConfig('login:token_'.$uniqid.'.created', time())					!== true) goto error;
	if ($tpl->setConfig('login:token_'.$uniqid.'.username', $pUsername) 			!== true) goto error;
	if ($tpl->setConfig('login:token_'.$uniqid.'.address', $_SERVER['REMOTE_ADDR']) !== true) goto error;
	if ($tpl->setConfig('user:user_'.$pUsername.'.last_login', time())				!== true) goto error;
	
	//$_SESSION['TOKEN'] = $uniqid;
	
	if (isset($_POST['keepLoggedIn']) && $_POST['keepLoggedIn'] == 'checked')
	{
		$tpl->setConfig('login:token_'.$uniqid.'.keep_logged_in', 'true');
		setcookie('_pi_control_login', $uniqid, time()+60*60*24*30);
	}
	else
		setcookie('_pi_control_login', $uniqid, time()+60*60*12);
	
	if (isset($_POST['referer']) && $_POST['referer'] != '')
		header('Location: ?'.urldecode($_POST['referer']));
	else
		header('Location: ?s=overview');
	
	exit();
	
	error:
	$tpl->assign('errorMsg', 'Fehler bei der Anmeldung!');
}

if (isset($_GET['logout']))
{
	if (isset($_COOKIE['_pi_control_login']))
	{
		$uniqid = $_COOKIE['_pi_control_login'];
		removeConfig('login:token_'.$uniqid);
		setcookie('_pi_control_login', '', time()-60);
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