<?php
$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/main/tpl.class.php')			or die($error_code['0x0001']);
(include_once LIBRARY_PATH.'/main/main.function.php')		or die($error_code['0x0002']);

$tpl = new PiTpl;
$tpl->setTpl($tpl);
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(false);
$tpl->setDrawFooter(false, $config, $errorHandler);

if (isset($_POST['submit'], $_POST['username'], $_POST['password']))
{
    $pUsername = strtolower(trim($_POST['username']));
    $pPassword = $_POST['password'];
	
	//$tpl->setConfig('user:user_willy.username', 'Willy');
	//$tpl->setConfig('user:user_willy.password', md5('1234'));
	//$tpl->setConfig('user:user_willy.last_login', time());
	//$tpl->setConfig('user:user_willy.created', time());
    
	if (($userinfo = $tpl->getConfig('user:user_'.$pUsername, 0)) === 0)
		goto error;
	
	if (!is_array($userinfo))
		goto error;
	
	if (strtolower($userinfo['username']) != $pUsername || $userinfo['password'] != md5($pPassword))
		goto error;
	
    $uniqid = generateUniqId();
    
    if ($tpl->setConfig('login:token_'.$uniqid.'.created', time())		!== true) goto error;
    if ($tpl->setConfig('login:token_'.$uniqid.'.username', $pUsername) !== true) goto error;
	if ($tpl->setConfig('user:user_'.$pUsername.'.last_login', time())	!== true) goto error;
	
    $_SESSION['TOKEN'] = $uniqid;
    
	if (isset($_POST['keepLoggedIn']) && $_POST['keepLoggedIn'] == 'checked')
	{
		$tpl->setConfig('login:token_'.$uniqid.'.keep_logged_in', 'true');
		setcookie('PiControlKeepLoggedIn', md5($_SERVER['REMOTE_ADDR'].$uniqid.$pUsername), time()+3600);
	}
    
    if (isset($_POST['referer']) && $_POST['referer'] != '')
        header('Location: ?'.urldecode($_POST['referer']));
	else
        header('Location: ?s=overview');
	
	exit();
	
	error:
	echo 'Fehler bei der Anmeldung!';
}

if (isset($_GET['logout']))
{
	$uniqid = $_SESSION['TOKEN'];
    removeConfig('login:token_'.$uniqid);
	unset($_SESSION['TOKEN']);
    session_destroy();
}

$tpl->assign('referer', isset($_REQUEST['referer']) ? urlencode($_REQUEST['referer']) : '');

$tpl->draw('login');
?>