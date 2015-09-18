<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

(include_once realpath(dirname(__FILE__)).'/../../init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/main/main.function.php')			or die('Fehler beim Laden!');

if (isset($_SESSION['TOKEN']))
{
	$uniqid = $_SESSION['TOKEN'];
    $tokenCreated = getConfig('login:token_'.$uniqid.'.created', 0);
	
	if ($tokenCreated == 0 || $tokenCreated < time()-43200)
	{
		removeConfig('login:token_'.$uniqid);
		unset($_SESSION['TOKEN']);
	    session_destroy();
	}
}

if (!isset($_SESSION['TOKEN']))
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