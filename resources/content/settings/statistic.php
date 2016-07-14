<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'statistic/statistic.class.php')	or die('Error: 0x0010');
$tpl->setHeaderTitle(_t('Einstellungen zur Statistik'));

$statistics = array();
$statisticIDs = array();

$controller = new StatisticController();
$controller->loadStatistics('statistic/');

foreach ($controller->getStatistics() as $statistic)
{
	$builder = new StatisticBuilder();
	$builder->loadFromFile($statistic);
	
	$array = $builder->getArray();
	$statistics[$builder->getId()] = array('array' => $array);
	$statisticIDs[] = $array['id'];
}

if (!isset($_GET['reset']) && (!isset($_GET['download'])))
{
	$hiddenStatistics = unserialize(htmlspecialchars_decode(getConfig('main:statistic.hidden', 'a:0:{}')));
	
	if (isset($_POST['submit']))
	{
		$hiddenStatistics = array_values(array_diff($statisticIDs, (isset($_POST['check'])) ? $_POST['check'] : array()));
		
		if (setConfig('main:statistic.hidden', htmlspecialchars(serialize($hiddenStatistics))) !== false)
			$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'));
		else
			$tpl->msg('error', _t('Fehler'), _t('Konnte Wert nicht in Konfigurationsdatei speichern!'));
	}
	
	foreach ($statistics as &$statistic)
	{
		$array = $statistic['array'];
		$statistic += array('visible' => (!in_array($array['id'], $hiddenStatistics)) ? true : false);
	}
	
	$tpl->assign('statistics', $statistics);
	
	$tpl->draw('settings/statistic');
}
elseif (isset($_GET['reset']))
{
	if (!isset($statistics[$_GET['reset']]))
		$tpl->msg('error', _t('Fehler'), _t('Der Verlauf konnte nicht gefunden werden: %s', $_GET['reset']));
	
	if (isset($_GET['confirm']) && $_GET['confirm'] == '')
	{
		if (isset($statistics[$_GET['reset']]))
		{
			if (($logFile = fopen(LOG_PATH.$statistics[$_GET['reset']]['array']['raw'].'.csv', 'w')) !== false)
			{
				$tpl->msg('success', _t('Verlauf zur&uuml;ckgesetzt'), _t('Verlauf wurde erfolgreich zur&uuml;ckgesetzt.'));
				fclose($logFile);
			}
			else
				$tpl->msg('error', _t('Fehler'), _t('Verlauf konnte nicht zur&uuml;ckgesetzt werden.'));
		}
	}
	
	$tpl->assign('log', $_GET['reset']);
	$tpl->assign('label', $statistics[$_GET['reset']]['array']['title']);
	
	$tpl->draw('settings/statistic_reset');
}
elseif (isset($_GET['download']))
	$tpl->redirect('api/v1/statistic_download.php?id='.$_GET['download']);
?>