<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php') 	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Error: 0x0001');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')	or die('Error: 0x0002');
(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Error: 0x0003');

$log = new LogStatistic();
$log->setFile(LOG_PATH.'statistic/cpufrequency.csv');
$log->setLimit(2016);
sleep(7);
$log->add(array(time(), rpi_getCpuClock()));
$log->close();
?>