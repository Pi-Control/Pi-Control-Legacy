<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'statistic/statistic.class.php')	or die('Error: 0x0010');
$tpl->setHeaderTitle(_t('Statistik'));

$statistics = array();
$hiddenStatistics = unserialize(htmlspecialchars_decode(getConfig('main:statistic.hidden', 'a:0:{}')));

$controller = new StatisticController($tpl);
$controller->loadStatistics('statistic/');

foreach ($controller->getStatistics() as $statistic)
{
	$builder = new StatisticBuilder();
	$builder->loadFromFile($statistic);
	
	$array = $builder->getArray();
	if (!in_array($builder->getId(), $hiddenStatistics))
		$statistics[] = array('array' => $array, 'json' => $builder->getJSON());
}

$tpl->assign('statistics', $statistics);
$tpl->assign('msgInfo', (count($hiddenStatistics) == count($controller->getStatistics())) ? 'invisible' : ((count($controller->getStatistics()) == 0) ? 'empty' : ''));

$tpl->draw('statistic');
?>