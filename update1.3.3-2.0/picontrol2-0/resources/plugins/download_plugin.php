<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')			or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Error: 0x0002');
(include_once LIBRARY_PATH.'plugin/plugin.function.php')	or die('Error: 0x0003');

$onlinePlugins = getOnlinePlugins();
$pluginId = (isset($_GET['id'])) ? $_GET['id'] : '';

$tpl = new PiTpl;
$tpl->setTpl($tpl);
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(false);
$tpl->setDrawFooter(false, $config, $errorHandler);

if (is_array($onlinePlugins))
{
	if (isset($onlinePlugins[$pluginId]))
	{
		if (($data = file_get_contents($config['url']['pluginDownload'].'&'.http_build_query(array('file' => $onlinePlugins[$pluginId]['versions'][$onlinePlugins[$pluginId]['latestVersion']]['download']['filename'])))) !== false)
		{
			if (file_put_contents(PLUGINS_PATH.'plugin.zip', $data, LOCK_EX) !== false)
			{
				if (md5_file(PLUGINS_PATH.'plugin.zip') == $onlinePlugins[$pluginId]['versions'][$onlinePlugins[$pluginId]['latestVersion']]['download']['checksum'])
				{
					if (!(file_exists(PLUGINS_PATH.$pluginId.'/') && is_dir(PLUGINS_PATH.$pluginId.'/')))
					{
						if (mkdir(PLUGINS_PATH.$pluginId.'/'))
						{
							$zip = new ZipArchive;
							
							if (($zipError = $zip->open(PLUGINS_PATH.'plugin.zip')) === true)
							{
								$zip->extractTo(PLUGINS_PATH.$pluginId.'/');
								$zip->close();
								unlink(PLUGINS_PATH.'plugin.zip');
								
								if (function_exists('apc_clear_cache')) apc_clear_cache();
								
								sleep(3); // Verhindere Cachen der init.php
								
								if (file_exists(PLUGINS_PATH.$pluginId.'/setup.php') && is_file(PLUGINS_PATH.$pluginId.'/setup.php'))
									$tpl->redirect(PLUGINS_PATH.$pluginId.'/setup.php');
								else
									$tpl->redirect('?s=discover_plugins&id='.$pluginId.'&installed');
							}
							else
								$errorMsg = _t('Leider ist ein Fehler beim entpacken des Plugins aufgetreten! Fehlercode: %s', $zipRrror);
						}
						else
							$errorMsg = _t('Der Ordner f&uuml;r das Plugin konnte nicht erstellt werden. Ordnerberechtigungen pr&uuml;fen und erneut versuchen. Sollte der Fehler weiterhin auftreten, schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiterhelfen kann.', 'https://willy-tech.de/kontakt/');
					}
					else
						$errorMsg = _t('Der Ordner f&uuml;r das Plugin existiert bereits. Bitte zun&auml;chst den Ordner l&ouml;schen.');
				}
				else
					$errorMsg = _t('Das Plugin wurde nicht vollst&auml;ndig heruntergeladen. Bitte versuche es erneut. Sollte der Fehler weiterhin auftreten, schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiterhelfen kann.', 'https://willy-tech.de/kontakt/');
			}
			else
				$errorMsg = _t('Konnte das Plugin nicht zwischenspeichern! Bitte schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.', 'https://willy-tech.de/kontakt/');
		}
		else
			$errorMsg = _t('Konnte das Plugin auf dem Server nicht finden! Bitte schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.', 'https://willy-tech.de/kontakt/');
	}
	else
		$errorMsg = _t('Konnte die PluginID auf dem Server nicht finden! Bitte schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.', 'https://willy-tech.de/kontakt/');
}
else
	$errorMsg = _t('Leider ist beim Abrufen der Plugins ein Fehler aufgetreten. Fehlercode: %s<br />Bitte schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.', $onlinePlugins, 'https://willy-tech.de/kontakt/');

if (isset($errorMsg))
{
	$tpl->assign('content', '<div class="inner-header"><span>'._t('Plugin Installation').'</span></div><div class="inner"><strong class="red">'.$errorMsg.'</strong></div><div class="inner-end"><a href="?s=discover_plugins&amp;id='.$pluginId.'" class="button">'._t('Zur&uuml;ck zum Plugin').'</a></div>');
	$tpl->draw('single_box');
}
?>