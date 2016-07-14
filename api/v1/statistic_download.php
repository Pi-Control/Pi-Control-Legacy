<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')				or die('Error: 0x0002');
(include_once LIBRARY_PATH.'statistic/statistic.function.php')			or die('Error: 0x0003');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')				or die('Error: 0x0004');
(include_once LIBRARY_PATH.'plugin/plugin.function.php')				or die('Error: 0x0005');

if (!isset($_GET['id']) || $_GET['id'] == '')
	exit();

$controller = new StatisticController();
$controller->loadStatistics();

if (($name = $controller->getStatisticName($_GET['id'])) === false)
	exit();

if (isset($_GET['plugin']) && trim($_GET['plugin']) != '')
	pluginLanguage(trim($_GET['plugin']));

$builder = new StatisticBuilder();
$builder->loadFromFile($name, (isset($_GET['plugin']) && trim($_GET['plugin']) != '') ? $_GET['plugin'] : NULL);
$statistic = $builder->getArray();

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=".$statistic['title'].".csv");
header("Pragma: no-cache");
header("Expires: 0");

$log = new LogStatistic();
$log->setFile(LOG_PATH.$statistic['raw'].'.csv');

function convertTimestampToISO(&$value, $key)
{
	$value[0] = date('c', trim($value[0]));
}

$header = array();
foreach ($statistic['columns'] as $column)
	$header[] = _t($column['downloadTitle']);

$output = fopen('php://output', 'w');

$data = $log->getAll();
array_walk($data, 'convertTimestampToISO');

fputcsv($output, $header);

foreach ($data as $entry)
	fputcsv($output, $entry);

fclose($output);
?>