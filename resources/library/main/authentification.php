<?php
if (!defined('PICONTROL')) exit();

(include_once realpath(dirname(__FILE__)).'/../../init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')			or die('Fehler beim Laden!');

$logout = true;

if (isset($_COOKIE['_pi-control_login']) || isset($_POST['token']))
{
	$uniqid = isset($_COOKIE['_pi-control_login']) ? $_COOKIE['_pi-control_login'] : $_POST['token'];
	$tokenCreated = getConfig('login:token_'.$uniqid.'.created', 0);
	$tokenRememberMe = getConfig('login:token_'.$uniqid.'.remember_me', 'false');
	$tokenUsername = getConfig('login:token_'.$uniqid.'.username', '');
	$tokenLastLogin = getConfig('user:user_'.$tokenUsername.'.last_login', 0);
	
	if ($tokenCreated == 0 || ($tokenCreated < time()-60*60*12 && $tokenRememberMe != 'true'))
	{
		removeConfig('login:token_'.$uniqid);
		
		if (isset($_COOKIE['_pi-control_login']))
			setcookie('_pi-control_login', '', time()-60);
	}
	elseif ($tokenLastLogin < time()-60*60)
	{
		setConfig('user:user_'.$tokenUsername.'.last_login', time());
		$logout = false;
	}
	else
		$logout = false;
}

if (isset($_POST['token']) && $logout === true)
{
	if (isset($authentificationMsg))
		die($authentificationMsg);
	else
	{
		$referer = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
		
		if ($referer != '')
			$referer = '&referer='.urlencode($referer);
		
		header('HTTP/1.0 403 Forbidden');
		exit();
	}
}
elseif ((!isset($_COOKIE['_pi-control_login']) || $logout === true) && !isset($_POST['token']))
{
	if (isset($authentificationMsg))
		die($authentificationMsg);
	else
	{
		$referer = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
		
		if ($referer != '')
			$referer = '&referer='.urlencode($referer);
		
		header('Location: ?i=login'.$referer);
		exit();
	}
}
?>