<?php
session_start();

/**
 * Start am 25.02.2015
 *
 * 25.02.2015: 4h
 * 26.02.2015: 2,5h
 * 27.02.2015: 1h
 * 01.03.2015: 4,5h
 * 02.03.2015: 3,75h
 * 03.03.2015: 6h
 */

(include_once realpath(dirname(__FILE__)).'/resources/main_config.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');

(include_once 'resources/library/main/tpl.class.php')	or exit('Fehler beim Einbinden');// DEBUG
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

function write_php_ini($array, $file)
{
	$res = array(';<?php', ';die();');
	
	ksort($array);
	
	foreach ($array as $key => $val)
	{
		asort($val);
		
		if (is_array($val))
		{
			$res[] = "\r\n[$key]";
			foreach ($val as $skey => $sval)
				$res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
		}
		else
			$res[] = "\r\n$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
	}
	
	$res[] = ';?>';
	
	safeFileRewrite($file, implode("\r\n", $res));
}

function safeFileRewrite($fileName, $dataToSave)
{
	if ($fp = fopen($fileName, 'w'))
	{
		$startTime = microtime();
		do
		{
			$canWrite = flock($fp, LOCK_EX);
			
			// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			if (!$canWrite)
				usleep(round(rand(0, 100)*1000));
		} while ((!$canWrite) && ((microtime()-$startTime) < 1000));
		
		// file was locked so now we can store information
		if ($canWrite)
		{
			fwrite($fp, $dataToSave);
			flock($fp, LOCK_UN);
		}
		fclose($fp);
	}
}

/*write_php_ini(	array(
					'access' => array('public' => 'true', 'protection' => 'false', 'protection_option' => 'all'),
					'overview' => array('reload_time' => 30, 'connected_devices' => 'true', 'weather' => 'true'),
					'temp' => array('temp' => 'false', 'celsius' => 65, 'mail' => '', 'mail_id' => '', 'mail_code' => '', 'shutdown' => 'false', 'option_timeout' => 0),
					'weather' => array('postcode' => '00000', 'country' => 'germany'),
					'other' => array('slim_header' => 'false', 'last_update_check' => 0, 'last_plugin_update_check' => 0, 'last_cron_execution' => 0, 'webserver_port' => 80, 'statistic_hide' => '')
				), 'resources/config/config.ini.php');*/

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
			$tpl->error('Fehler', 'Leider existiert die angeforderte Seite nicht.');
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
	<div class="inner-bottom"><strong class="red">Leider ist beim Aufbau der Seite ein Fehler aufgetreten!</strong><br /><br />'.implode("<br />\n", $errorHandler).'</div>
	<div class="inner">Bitte melde das Problem.</div>
</div>
</body>
</html>';
}

//$tpl->showDebug();
?>