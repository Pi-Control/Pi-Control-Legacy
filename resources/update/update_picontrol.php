<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../init.php');
(include_once LIBRARY_PATH.'main/tpl.class.php') or die('Fehler beim Laden!');
(include_once LIBRARY_PATH.'main/main.function.php') or die('Fehler beim Laden!');

$update = checkUpdate();

$tpl = new PiTpl;
$tpl->setTpl($tpl);
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(false);
$tpl->setDrawFooter(false, $config, $errorHandler);

if (is_array($update))
{
	if (($data = file_get_contents($config['url']['updateDownload'].'&'.http_build_query(array('file' => $update['filename'])))) !== false)
	{
		if (file_put_contents(UPDATE_PATH.'update.zip', $data, LOCK_EX) !== false)
		{
			if (md5_file(UPDATE_PATH.'update.zip') == $update['checksum'])
			{
				$zip = new ZipArchive;
				
				if (($zipError = $zip->open(UPDATE_PATH.'update.zip')) === true)
				{
					$zip->extractTo(PICONTROL_PATH);
					$zip->close();
					unlink(UPDATE_PATH.'update.zip');
					
					if (function_exists('apc_clear_cache')) apc_clear_cache();
					
					sleep(3); // Verhindere Cachen der init.php
					
					if (file_exists(UPDATE_PATH.'setup.php') && is_file(UPDATE_PATH.'setup.php'))
						$tpl->redirect(UPDATE_PATH.'setup.php');
					else
						$tpl->redirect('?s=settings&do=update&complete');
				}
				else
					$errorMsg = 'Leider ist ein Fehler beim entpacken der Aktualisierung aufgetreten! Fehlercode: '.$zipRrror;
			}
			else
				$errorMsg = 'Die Aktualisierung wurde nicht vollst&auml;ndig heruntergeladen. Bitte versuche es erneut. Sollte der Fehler weiterhin auftreten, schreibe mir unter <a href="http://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiterhelfen kann.';
		}
		else
			$errorMsg = 'Konnte die Aktualisierung nicht zwischenspeichern! Bitte schreibe mir unter <a href="http://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';
	}
	else
		$errorMsg = 'Konnte die Aktualisierung auf dem Server nicht finden! Bitte schreibe mir unter <a href="http://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';
}
else
	$errorMsg = 'Leider ist w&auml;hrend der Aktualisierung ein Fehler aufgetreten: '.$update.'<br />Bitte schreibe mir unter <a href="http://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';

if (isset($errorMsg))
{
	$tpl->assign('content', '<div class="inner-header"><span>Aktualisierung</span></div><div class="inner red">'.$errorMsg.'</div><div class="inner-end"><a href="?s=settings&amp;do=update" class="button">Zur&uuml;ck zur Aktualisierung</a></div>');
	$tpl->draw('single_box');
}
?>