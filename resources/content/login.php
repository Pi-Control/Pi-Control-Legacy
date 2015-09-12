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
    $pUsername = trim($_POST['username']);
    $pPassword = $_POST['password'];
    
    if ($pUsername != '' && $pPassword != '')
    {
        $uniqid = generateUniqId();
        
        $tpl->setConfig('login:token_'.$uniqid.'.created', time());
        $tpl->setConfig('login:token_'.$uniqid.'.username', $pUsername);
        
        $_SESSION['TOKEN'] = $uniqid;
        
        if (isset($_POST['referer']) && $_POST['referer'] != '')
        {
            header('Location: ?'.urldecode($_POST['referer']));
            exit();
        }
    }
}

if (isset($_GET['logout']))
{
	$uniqid = $_SESSION['TOKEN'];
    removeConfig('login:token_'.$uniqid);
	unset($_SESSION['TOKEN']);
    session_destroy();
}

if (isset($_REQUEST['referer']))
    $tpl->assign('referer', isset($_REQUEST['referer']) ? urlencode($_REQUEST['referer']) : '');

$tpl->draw('login');
?>