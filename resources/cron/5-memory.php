<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php') 	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Error: 0x0001');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')	or die('Error: 0x0002');
(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Error: 0x0003');

$memories = rpi_getMemoryInfo();
$memory = end($memories);

$log = new LogStatistic();
$log->setFile(LOG_PATH.'statistic/memory.csv');
$log->setLimit(2016);
$log->add(array(time(), $memory['total'], $memory['used']));
$log->close();
?>