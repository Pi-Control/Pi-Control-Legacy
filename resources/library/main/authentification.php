<?php
if (!defined('PICONTROL')) exit();

(include_once realpath(dirname(__FILE__)).'/../../init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/main/main.function.php')			or die('Fehler beim Laden!');

$logout = true;

if (isset($_COOKIE['_pi-control_login']))
{
	$uniqid = $_COOKIE['_pi-control_login'];
	$tokenCreated = getConfig('login:token_'.$uniqid.'.created', 0);
	$tokenKeepLoggedIn = getConfig('login:token_'.$uniqid.'.remember_me', 'false');
	$tokenUsername = getConfig('login:token_'.$uniqid.'.username', '');
	$tokenLastLogin = getConfig('user:user_'.$tokenUsername.'.last_login', 0);
	
	if ($tokenCreated == 0 || ($tokenCreated < time()-60*60*12 && $tokenKeepLoggedIn != 'true'))
	{
		removeConfig('login:token_'.$uniqid);
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

if (!isset($_COOKIE['_pi-control_login']) || $logout === true)
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