<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../init.php');
(include_once LIBRARY_PATH.'main/tpl.class.php') or die('Fehler beim Laden!');
(include_once LIBRARY_PATH.'main/main.function.php') or die('Fehler beim Laden!');
(include_once LIBRARY_PATH.'plugin/plugin.function.php') or die('Fehler beim Laden!');

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
		if (($data = file_get_contents($config['url']['pluginDownload'].'&'.http_build_query(array('file' => $onlinePlugins[$pluginId]['versions'][$onlinePlugins[$pluginId]['latestVersion']]['update']['filename'])))) !== false)
		{
			if (file_put_contents(PLUGINS_PATH.'plugin.zip', $data, LOCK_EX) !== false)
			{
				if (md5_file(PLUGINS_PATH.'plugin.zip') == $onlinePlugins[$pluginId]['versions'][$onlinePlugins[$pluginId]['latestVersion']]['update']['checksum'])
				{
					if (file_exists(PLUGINS_PATH.$pluginId.'/') && is_dir(PLUGINS_PATH.$pluginId.'/'))
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
								$tpl->redirect('?s=discover_plugins&id='.$pluginId.'&updated');
						}
						else
							$errorMsg = 'Leider ist ein Fehler beim entpacken des Plugins aufgetreten! Fehlercode: '.$zipRrror;
					}
					else
						$errorMsg = 'Der Ordner f&uuml;r das Plugin existiert nicht. Bitte installiere zun&auml;chst das Plugin.';
				}
				else
					$errorMsg = 'Das Plugin wurde nicht vollst&auml;ndig heruntergeladen. Bitte versuche es erneut. Sollte der Fehler weiterhin auftreten, schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiterhelfen kann.';
			}
			else
				$errorMsg = 'Konnte das Plugin nicht zwischenspeichern! Bitte schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';
		}
		else
			$errorMsg = 'Konnte das Plugin auf dem Server nicht finden! Bitte schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';
	}
	else
		$errorMsg = 'Konnte die PluginID auf dem Server nicht finden! Bitte schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';
}
else
	$errorMsg = 'Leider ist beim Abrufen der Plugins ein Fehler aufgetreten. Fehlercode: '.$onlinePlugins.'<br />Bitte schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';

if (isset($errorMsg))
{
	$tpl->assign('content', '<div class="inner-header"><span>Plugin Aktualisierung</span></div><div class="inner"><strong class="red">'.$errorMsg.'</strong></div><div class="inner-end"><a href="?s=discover_plugins&amp;id='.$pluginId.'" class="button">Zur&uuml;ck zum Plugin</a></div>');
	$tpl->draw('single_box');
}
?>