<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')				or die('Error: 0x0002');
(include_once LIBRARY_PATH.'statistic/statistic.function.php')			or die('Error: 0x0003');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0004');
(include_once LIBRARY_PATH.'plugin/plugin.function.php')				or die('Error: 0x0005');

$api = new API;

if (isset($_POST['id']))
{
	$controller = new StatisticController();
	$controller->loadStatistics();
	
	if (($name = $controller->getStatisticName($_POST['id'])) !== false)
	{
		if (isset($_POST['plugin']) && trim($_POST['plugin']) != '')
			pluginLanguage(trim($_POST['plugin']));
		
		$builder = new StatisticBuilder();
		$builder->loadFromFile($name, (isset($_POST['plugin']) && trim($_POST['plugin']) != '') ? $_POST['plugin'] : NULL);
		$statistic = $builder->getArray();
		
		$log = new LogStatistic();
		$log->setFile(LOG_PATH.$statistic['raw'].'.csv');
		
		$arr = $info = array();
		
		foreach ($statistic['columns'] as $column)
			$arr['cols'][] = array('id' => '', 'label' => _t($column['label']), 'type' => $column['type']);
		
		getRowsFromLog($arr, $info, $log->getAll(), $statistic['columns'], $statistic['cycle']);
		
		if (isset($arr['rows']))
		{
			if (isset($_POST['type']) && $_POST['type'] == 'googleChart')
				$arr['rows'] = convertForGoogleChart($arr['rows']);
			
			$arr['rows'] = array_slice($arr['rows'], -2016);
			$arr['periods'] = $info['periods'];
			
			foreach (array('min', 'max') as $type)
			{
				if ($statistic['limits'][$type]['use'] == 'multiply')
					$arr[$type] = round($info[$type] * $statistic['limits'][$type]['value']);
				elseif ($statistic['limits'][$type]['use'] == 'fix')
				{
					if ($statistic['limits'][$type]['fix'] == true)
						$arr[$type] = $statistic['limits'][$type]['value'];
					else
						$arr[$type] = round($info[$type]);
				}
			}
			
			$api->addData('statistic', $arr);
		}
		else
			$api->setError('error', 'Empty data.');
	}
	else
		$api->setError('error', 'Data not found.');
}
else
{
	$statistics = array();
	$hiddenStatistics = unserialize(htmlspecialchars_decode(getConfig('main:statistic.hidden', 'a:0:{}')));
	
	$controller = new StatisticController();
	$controller->loadStatistics();
	
	foreach ($controller->getStatistics() as $statistic)
	{
		$builder = new StatisticBuilder();
		$builder->loadFromFile($statistic);
		
		$array = $builder->getArray();
		if (!in_array($builder->getId(), $hiddenStatistics))
			$statistics[] = array('array' => $array);
	}
	
	$api->addData('statistics', $statistics);
	$api->addData('hidden', $hiddenStatistics);
}

$api->display();
?>