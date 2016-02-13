<?php
if (PHP_SAPI != 'cli') exit();
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');
(include_once LIBRARY_PATH.'curl/curl.class.php')			or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');

if (getConfig('main:notificationPB.enabled', false))
{
    $token = getConfig('main:notificationPB.token', '');
    $lastPush = json_decode(htmlspecialchars_decode(getConfig('main:notificationPB.lastPush', '{}')), true);
		
    if (getConfig('main:notificationPB.picontrolVersionEnabled', 'false') == 'true' && (getConfig('cron:updateCheck.picontrol', 0)+21600) < time())
    {
        $picontrolUpdate = checkUpdate();
        
        if (!isset($lastPush['picontrol']))
            $lastPush['picontrol'] = array('time' => 0, 'value' => 0, 'notification_iden' => '');
        
        if (!is_array($picontrolUpdate))
            setConfig('cron:updateCheck.picontrol', time());
        else
        {
            if ($lastPush['picontrol']['value'] < $picontrolUpdate['versioncode'] && $lastPush['picontrol']['time']+21600 < time())
            {
                setConfig('cron:updateCheck.picontrol', time()-86400);
                
				$curl = new cURL('https://api.pushbullet.com/v2/pushes', HTTP_POST);
				$curl->addHeader(array('Authorization: Bearer '.$token, 'Content-Type: application/json'));
				$curl->setParameterRaw(json_encode(array('type' => 'note', 'title' => 'Pi Control | Aktualisierung verfügbar', 'body' => 'Pi Control Version '.$picontrolUpdate['version'].' steht ab sofort zum herunterladen bereit.')));
				$curl->execute();
                
                // Reduziere Traffic, da selbst bei Fehler erst wieder nach 21600 Sek. geprüft wird
                $lastPush['picontrol']['time'] = time();
                
				if ($curl->getResult($data) == JSON_ERROR_NONE)
				{
					if (!isset($data['error']))
					{
						$lastPush['picontrol']['value'] = $picontrolUpdate['versioncode'];
                    	$lastPush['picontrol']['notification_iden'] = $return['iden'];
					}
				}
            }
        }
    }
	
    if (getConfig('main:notificationPB.cpuTemperatureEnabled', 'false') == 'true')
    {
        $temp = rpi_getCoreTemprature();
        
        if (!isset($lastPush['cpu_temp']))
            $lastPush['cpu_temp'] = array('time' => 0, 'value' => 0, 'notification_iden' => '');
        
        if ($temp > getConfig('main:notificationPB.cpuTemperatureMaximum', 65) && $lastPush['cpu_temp']['value'] < ($temp + 5) && $lastPush['cpu_temp']['time']+300 < time())
        {
			$curl = new cURL('https://api.pushbullet.com/v2/pushes', HTTP_POST);
			$curl->addHeader(array('Authorization: Bearer '.$token, 'Content-Type: application/json'));
			$curl->setParameterRaw(json_encode(array('type' => 'note', 'title' => 'Pi Control | Temperaturüberschreitung', 'body' => 'Dein Pi Control meldet eine erhöhte Temperatur der CPU von '.$temp.' °C.')));
			$curl->execute();
			
			if ($curl->getResult($data) == JSON_ERROR_NONE)
			{
				if (!isset($data['error']))
				{
	                $lastPush['cpu_temp']['time'] = time();
	                $lastPush['cpu_temp']['value'] = $temp;
	                $lastPush['cpu_temp']['notification_iden'] = $data['iden'];
				}
			}
        }
        elseif (getConfig('main:notificationPB.cpuTemperatureMaximum', 65) > $temp)
            $lastPush['cpu_temp']['value'] = 0;
    }
	
    if (getConfig('main:notificationPB.memoryUsedEnabled', 'false') == 'true')
    {
        $memory = rpi_getMemoryInfo();
        $percent = $memory[count($memory)-1]['percent'];
        
        if (!isset($lastPush['memory']))
            $lastPush['memory'] = array('time' => 0, 'value' => 0, 'notification_iden' => '');
        
        if (getConfig('main:notificationPB.memoryUsedLimit', 80) < $percent && $lastPush['memory']['value'] < ((100 - $percent) / 10 + $percent) && $lastPush['memory']['time']+3600 < time())
        {
			$curl = new cURL('https://api.pushbullet.com/v2/pushes', HTTP_POST);
			$curl->addHeader(array('Authorization: Bearer '.$token, 'Content-Type: application/json'));
			$curl->setParameterRaw(json_encode(array('type' => 'note', 'title' => 'Pi Control | Speicherverbrauch', 'body' => 'Dein Pi Control meldet einen Speicherverbrauch von '.$percent.'%.')));
			$curl->execute();
			
			if ($curl->getResult($data) == JSON_ERROR_NONE)
			{
				if (!isset($data['error']))
				{
	                $lastPush['memory']['time'] = time();
	                $lastPush['memory']['value'] = $percent;
	                $lastPush['memory']['notification_iden'] = $data['iden'];
				}
			}
        }
        elseif (getConfig('main:notificationPB.memoryUsedLimit', 80) > $percent)
            $lastPush['memory']['value'] = 0;
    }
    
    setConfig('main:notificationPB.lastPush', htmlspecialchars(json_encode($lastPush)));
}
?>