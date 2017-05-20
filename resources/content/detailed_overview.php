<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'main/rpi.function.php') or die('Error: 0x0010');
(include_once LIBRARY_PATH.'cache/cache.class.php') or die('Error: 0x0011');
$tpl->setHeaderTitle(_t('Detaillierte &Uuml;bersicht'));

$cpu = rpi_getCPULoad(true, true);
$ram = rpi_getMemoryUsage();
$memory = rpi_getMemoryInfo();

$tpl->assign('time', date('d.m.Y H:i:s', time()));
$tpl->assign('timezone', date('e (P)', time()));
$tpl->assign('run_time', getDateFormat(rpi_getRuntime()));
$tpl->assign('start_time', formatTime(time() - rpi_getRuntime()));
$tpl->assign('serial', rpi_getRpiSerial());
$tpl->assign('revision', rpi_getRpiRevision());
$tpl->assign('distribution', rpi_getDistribution());
$tpl->assign('kernel', rpi_getKernelVersion());
$tpl->assign('webserver', $_SERVER['SERVER_SOFTWARE']);
$tpl->assign('php', PHP_VERSION);
$tpl->assign('whoami', exec('whoami'));
$tpl->assign('cpu_clock', rpi_getCpuClock().' MHz');
$tpl->assign('cpu_max_clock', rpi_getCpuMaxClock().' MHz');
$tpl->assign('cpu_load', $cpu['cpu']);
$tpl->assign('cpu_loads', (count($cpu) == 1) ? array() : array_slice($cpu, 1));
$tpl->assign('cpu_type', rpi_getCPUType());
$tpl->assign('cpu_model', rpi_getCpuModel());
$tpl->assign('cpu_temp', numberFormat(rpi_getCoreTemprature()).' &deg;C');
$tpl->assign('ram_percentage', $ram['percent'].'%');
$tpl->assign('memory', $memory);
$tpl->assign('memory_count', count($memory));
$tpl->assign('runningTasksCount', rpi_getCountRunningTasks());
$tpl->assign('installedPackagesCount', rpi_getCountInstalledPackages());

$tpl->draw('detailed_overview');
?>