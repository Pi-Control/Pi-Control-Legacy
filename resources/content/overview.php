<?php
$tpl->setHeaderTitle(_t('&Uuml;bersicht'));

if (isset($_GET['action']) && !empty($_GET['action']))
{
	switch ($_GET['action'])
	{
		case 'system_shutdown':
			if (is_array($SSHReturn = $tpl->executeSSH('sudo /sbin/shutdown -h now', true, 1)))
				list ($SSHError, $SSHOutput) = $SSHReturn;
			
			if (empty($SSHError))
				$tpl->redirect('resources/content/overview_action.php?shutdown');
			else
				$tpl->msg('red', $tpl->_t('Herunterfahren nicht möglich'), nl2br($SSHError));
				break;
		case 'system_restart':
			if (is_array($SSHReturn = $tpl->executeSSH('sudo /sbin/shutdown -r now', true, 1)))
				list ($SSHError, $SSHOutput) = $SSHReturn;
			
			if (empty($SSHError))
				$tpl->redirect('resources/content/overview_action.php?restart');
			else
				$tpl->msg('red', $tpl->_t('Neustarten nicht möglich'), nl2br($SSHError));
				break;
	}
}

$ram = rpi_getMemoryUsage();
$memory = rpi_getMemoryInfo();

$tpl->assign('js_variables', 'var reload_timeout = '.($tpl->getConfig('main:other.overview_reload_time', 30)*1000).'; var overview_path = \'api/v1/overview.php\'');
$tpl->assign('show_weather', $tpl->getConfig('main:overview.weather', 'true'));
$tpl->assign('weather', ($tpl->getConfig('main:overview.weather') === 'true') ? getWeather($tpl->getConfig('main:weather.postcode', 00000)) : '');
$tpl->assign('run_time', getDateFormat(rpi_getRuntime()));
$tpl->assign('start_time', formatTime(time() - rpi_getRuntime()));
$tpl->assign('cpu_clock', rpi_getCpuClock().' MHz');
$tpl->assign('cpu_load', rpi_getCPULoad().'%');
$tpl->assign('cpu_type', rpi_getCPUType());
$tpl->assign('cpu_temp', numberFormat(rpi_getCoreTemprature()).' &deg;C');
$tpl->assign('ram_percentage', $ram['percent'].'%');
$tpl->assign('memory', end($memory));
$tpl->assign('usb_devices', ($tpl->getConfig('main:overview.connected_devices', 'true') === 'true') ? rpi_getUsbDevices() : '');

$tpl->draw('overview');
?>