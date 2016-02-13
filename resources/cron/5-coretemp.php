<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'statistic/statistic.class.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');

$log = new LogStatistic();
$log->setFile(LOG_PATH.'statistic/coretemp.csv');
$log->setLimit(2016);
$log->add(array(time(), rpi_getCoreTemprature()));
$log->close();
?>