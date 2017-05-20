<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')							or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/main.function.php')						or die('Error: 0x0002');
(include_once LIBRARY_PATH.'log/log.function.php')							or die('Error: 0x0003');
(include_once LIBRARY_PATH.'log/log.class.php')								or die('Error: 0x0004');

if (!isset($_GET['log']) || ($logName = trim(urldecode($_GET['log']))) == '')
	exit();

$tpl = new PiTpl;
$tpl->setTpl($tpl);

$logController = new LogController('/var/log');
$log = $logController->getLogFromRelativePath($logName);

if (!$log instanceof LogEntry)
	exit();

if ($log->getReadable() === false)
	exit();

$readLog = $logController->readLog($log->getPath());
$filename = ((substr($log->getFilename(), -3) == '.gz') ? substr($log->getFilename(), 0, -3) : $log->getFilename()).'.txt';

header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=".$filename);
header("Pragma: no-cache");
header("Expires: 0");

$output = fopen('php://output', 'w');
fwrite($output, $readLog['output']);
fclose($output);
?>