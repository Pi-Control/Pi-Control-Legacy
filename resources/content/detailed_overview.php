<?php
$tpl->setHeaderTitle(_t('Detaillierte &Uuml;bersicht'));

$ram = rpi_getMemoryUsage();
$memory = rpi_getMemoryInfo();
$users = new Cache('users', 'rpi_getAllUsers');

$users->load();

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
$tpl->assign('cpu_load', rpi_getCPULoad().'%');
$tpl->assign('cpu_type', rpi_getCPUType());
$tpl->assign('cpu_model', rpi_getCpuModel());
$tpl->assign('cpu_temp', numberFormat(rpi_getCoreTemprature()).' &deg;C');
$tpl->assign('ram_percentage', $ram['percent'].'%');
$tpl->assign('memory', $memory);
$tpl->assign('memory_count', count($memory));
$tpl->assign('all_users', $users->getContent());
$tpl->assign('users_cache_hint', $users->displayHint());

$tpl->draw('detailed_overview');
?>