<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php') 	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')	or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Error: 0x0002');

$ram = rpi_getMemoryUsage();

$log = new LogStatistic();
$log->setFile(LOG_PATH.'statistic/ram.csv');
$log->setLimit(2016);
$log->add(array(time(), $ram['percent']));
$log->close();
?>