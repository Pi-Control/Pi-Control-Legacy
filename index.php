<?php
(include_once realpath(dirname(__FILE__)).'/resources/init.php') or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');

(include_once LIBRARY_PATH.'/main/tpl.class.php')		or die($error_code['0x0001']);
(include_once LIBRARY_PATH.'/main/main.function.php')	or die($error_code['0x0002']);
(include_once LIBRARY_PATH.'/main/rpi.function.php')	or die($error_code['0x0003']);
(include_once LIBRARY_PATH.'/main/sites.php')			or die($error_code['0x0004']);

$tpl = new PiTpl;
$tpl->setTpl($tpl);
//$tpl->setLanguage('en');
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(true);
$tpl->setDrawFooter(true, $config, $errorHandler);
//$tpl->setHeaderTitleFormat('Pi Control | %s');

try
{
	// Lade Content
	if (isset($_GET['s']) && isset($site[$_GET['s']]) && file_exists(CONTENT_PATH.'/'.$site[$_GET['s']]))
	{
		include_once CONTENT_PATH.'/'.$site[$_GET['s']];
	}
	else
	{
		if (isset($_GET['s']) && (!isset($site[$_GET['s']]) || file_exists(CONTENT_PATH.'/'.$site[$_GET['s']]) === false))
		{
			$tpl->setHeaderTitle(_t('Fehler'));
			$tpl->error(_t('Fehler'), _t('Leider existiert die angeforderte Seite nicht.'));
		}
		else
			include_once CONTENT_PATH.'/overview.php';
	}
}
catch(Exception $e)
{
	$errorHandler[] = 'Fehler [TPL]: '.$e->getFile().':'.$e->getLine().' => '.$e->getMessage();
	
	echo '<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Pi Control</title>
	<style type=text/css>body{color:#333;background:#f6f6f6;font-family:Arial,Helvetica,Verdana,sans-serif;font-size:13px;margin:10px;overflow:scroll;overflow-x:auto;padding:0}.box{background:#fcc;margin:0 auto 0;max-width:800px}.inner-header{border-bottom:1px solid #fc0517;font-size:20px;padding:20px}.inner-bottom{border-bottom:1px solid #fbb;padding:20px}.inner{padding:20px}</style>
</head>
<body>
<div class="box">
	<div class="inner-header"><span>Fehler</span></div>
	<div class="inner-bottom"><strong class="red">Leredider ist beim Aufbau der Seite ein Fehler aufgetreten!</strong><br /><br />'.implode("<br />\n", $errorHandler).'</div>
	<div class="inner">Bitte melde das Problem.</div>
</div>
</body>
</html>';
}

//$tpl->showDebug();
?>