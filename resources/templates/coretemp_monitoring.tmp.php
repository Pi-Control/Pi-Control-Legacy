<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php') 	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');
(include_once LIBRARY_PATH.'cron/cron.class.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0003');
(include_once LIBRARY_PATH.'curl/curl.class.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0004');

$cron = new Cron;
$cron->setName('coretemp_monitoring');

$coreTemp = rpi_getCoreTemprature()+5;

if ($cron->isExists() === true && $coreTemp > getConfig('main:monitoringCpuTemp.maximum', 60))
{
	$ifOption = false;
	
	if ((getConfig('cron:execution.monitoringCpuTemp', 0)+3600) <= time())
	{
		if (getConfig('main:monitoringCpuTemp.emailEnabled', 'false') == 'true' && getConfig('main:monitoringCpuTemp.email', '') != '' && getConfig('main:monitoringCpuTemp.code', '') != '' && getConfig('main:monitoringCpuTemp.id', '') != '')
		{
			$curl = new cURL($config['url']['temperatureMonitoring'], HTTP_POST);
			$curl->addParameter(array('type' => 'send'));
			$curl->addParameter(array('id' => getConfig('main:monitoringCpuTemp.id', '')));
			$curl->addParameter(array('code' => getConfig('main:monitoringCpuTemp.code', '')));
			$curl->addParameter(array('email' => getConfig('main:monitoringCpuTemp.email', '')));
			$curl->addParameter(array('label' => getConfig('main:main.label', 'Raspberry Pi')));
			$curl->addParameter(array('maximum' => getConfig('main:monitoringCpuTemp.maximum', 60)));
			$curl->addParameter(array('coretemp' => $coreTemp));
			$curl->execute();
			
			if ($curl->getStatusCode() == 200)
				$ifOption = true;
		}
		
		if (getConfig('main:monitoringCpuTemp.shellEnabled', 'false') == 'true' && getConfig('main:monitoringCpuTemp.shell', '') != '')
		{
			shell_exec(base64_decode(getConfig('main:monitoringCpuTemp.shell', '')));
			$ifOption = true;
		}
	}
	
	if ($ifOption === true)
		setConfig('cron:execution.monitoringCpuTemp', time());
}
?>