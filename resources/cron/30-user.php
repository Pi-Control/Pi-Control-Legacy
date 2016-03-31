<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php') 	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Error: 0x0001');

$loggedInUsers = getConfig('login');
unset($loggedInUsers['login']);

foreach ($loggedInUsers as $token => $user)
{
	if ($user['created'] < time()-60*60*12 && !(isset($user['remember_me']) && $user['remember_me'] == 'true'))
		removeConfig('login:'.$token);
}
?>