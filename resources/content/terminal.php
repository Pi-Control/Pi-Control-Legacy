<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle('Terminal');

$jsTranslations = array();
$jsTranslations[] = 'Verbindung herstellen...';
$jsTranslations[] = 'Bitte gebe einen Befehl ein!';
$jsTranslations[] = 'Verbunden';
$jsTranslations[] = 'Verbindung getrennt';
$jsTranslations[] = 'Fehler aufgetreten!';
$jsTranslations[] = 'Verbindung erneut herstellen';
$jsTranslations[] = 'Verbindung getrennt<br />(Anmeldung an anderem Fenster)';
$jsTranslations[] = 'Verbindung getrennt<br />(Keine Berechtigung)';

$selectedPort = (isset($_GET['port'])) ? $_GET['port'] : 9001;
$ports = array();

if (isset($_GET['kill']) && $_GET['kill'])
{
	if (($terminal = getConfig('terminal:port_'.$_GET['kill'], '')) != '')
	{
		removeConfig('terminal:port_'.$_GET['kill']);
		exec('kill -9 '.$terminal['pid']);
		
		$tpl->msg('success', _t('Terminal beendet'), _t('Das Terminal wurde erfolgreich beendet.'));
	}
}

if ($tpl->getSSHResource(1) !== false)
{
	$termials = getConfig('terminal', array());
	
	$ports = array(
					array('port' => 9001, 'active' => false),
					array('port' => 9002, 'active' => false),
					array('port' => 9003, 'active' => false),
					array('port' => 9004, 'active' => false),
					array('port' => 9005, 'active' => false)
				);
	
	foreach ($ports as $index => $port)
	{
		if (($terminal = getConfig('terminal:port_'.$port['port'], '')) != '')
		{
			if (shell_exec('netstat -atn | grep :'.$port['port']) == '')
			{
				removeConfig('terminal:port_'.$port['port']);
				exec('kill -9 '.$terminal['pid']);
			}
		}
		
		if (isset($termials['port_'.$port['port']]) && shell_exec('netstat -atn | grep :'.$port['port']) != '')
			$ports[$index]['active'] = true;
	}
}

$tpl->assign('port', $selectedPort);
$tpl->assign('ports', $ports);
$tpl->assign('cookie', substr($_COOKIE['_pi-control_login'], 0, 16));
$tpl->assign('jsTranslations', $jsTranslations);

$tpl->draw('terminal');
?>