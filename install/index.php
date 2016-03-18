<?php
DEFINE('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/resources/init.php') or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');

(include_once LIBRARY_PATH.'main/tpl.class.php')			or die($error_code['0x0001']);
(include_once LIBRARY_PATH.'main/main.function.php')		or die($error_code['0x0002']);
(include_once LIBRARY_PATH.'main/sites.php')				or die($error_code['0x0003']);
(include_once LIBRARY_PATH.'main/password.function.php')	or die($error_code['0x0004']);
(include_once LIBRARY_PATH.'install/install.function.php')	or die($error_code['0x0005']);

setGlobalLanguage();

$tpl = new PiTpl;
$tpl->setTpl($tpl);
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(true);
$tpl->setDrawFooter(true, $config, $errorHandler);

try
{
	// Lade Content
	if (isset($_GET['s']) && isset($site[$_GET['s']]) && file_exists(CONTENT_PATH.$site[$_GET['s']]))
		include_once CONTENT_PATH.$site[$_GET['s']];
	else
	{
		if (isset($_GET['s']) && (!isset($site[$_GET['s']]) || file_exists(CONTENT_PATH.$site[$_GET['s']]) === false))
		{
			$tpl->setHeaderTitle(_t('Fehler'));
			$tpl->error(_t('Fehler'), _t('Leider existiert die angeforderte Seite nicht.'));
		}
		else
			include_once CONTENT_PATH.'install.php';
	}
	
	if ($tpl->tplDraw === false)
		$tpl->draw();
}
catch(Exception $e)
{
	$errorHandler[] = 'Fehler [TPL]: '.$e->getFile().':'.$e->getLine().' => '.$e->getMessage();
	
	echo '<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Pi Control</title>
	<style type="text/css">body{color:#333;background:#EEE;font-family:Arial,Helvetica,Verdana,sans-serif;font-size:13px;margin:10px;overflow:scroll;overflow-x:auto;padding:0}.box{background:#FFF;margin:0 auto;max-width:800px;border-radius:2px}.inner-header{display:inline-block;font-size:18px;font-weight:bold;padding:15px;color:#F44336}.inner{padding:0px 15px 15px 15px}</style>
</head>
<body>
<div class="box">
	<div class="inner-header">Fehler</div>
	<div class="inner">Leider ist beim Aufbau der Seite ein Fehler aufgetreten!<br /><br />'.implode("<br />\n", $errorHandler).'</div>
	<div class="inner">Bitte melde das Problem.</div>
</div>
</body>
</html>';
}
?>