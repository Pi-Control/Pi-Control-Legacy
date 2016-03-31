<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'main/rpi.function.php') or die('Error: 0x0010');
(include_once LIBRARY_PATH.'cache/cache.class.php') or die('Error: 0x0011');
$tpl->setHeaderTitle(_t('&Uuml;bersicht'));

$ram = rpi_getMemoryUsage();
$memory = rpi_getMemoryInfo();

$weather = new Cache('weather', 'getWeather');
$usbDevices = new Cache('usb_devices', 'rpi_getUsbDevices');

$weather->load();
$usbDevices->load();

$tpl->assign('js_variables', 'var reload_timeout = '.(getConfig('main:overview.interval', 30)*1000).';');
$tpl->assign('show_weather', (getConfig('main:weather.activation', 'false') == 'true') ? true : false);
$tpl->assign('weather', (getConfig('main:weather.activation', 'false') == 'true') ? $weather->getContent() : '');
$tpl->assign('weather_cache_hint', $weather->displayHint());
$tpl->assign('run_time', getDateFormat(rpi_getRuntime()));
$tpl->assign('start_time', formatTime(time() - rpi_getRuntime()));
$tpl->assign('cpu_clock', rpi_getCpuClock().' MHz');
$tpl->assign('cpu_load', rpi_getCPULoad().'%');
$tpl->assign('cpu_type', rpi_getCPUType());
$tpl->assign('cpu_temp', numberFormat(rpi_getCoreTemprature()).' &deg;C');
$tpl->assign('ram_percentage', $ram['percent'].'%');
$tpl->assign('memory', end($memory));
$tpl->assign('usb_devices', (getConfig('main:overview.showDevices', 'true') == 'true') ? $usbDevices->getContent() : '');
$tpl->assign('usb_devices_cache_hint', $usbDevices->displayHint());

$tpl->draw('overview');
?>