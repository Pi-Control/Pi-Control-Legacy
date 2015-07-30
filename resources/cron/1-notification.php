<?php
(include_once realpath(dirname(__FILE__)).'/../main_config.php') 	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/main/classes.php')						or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'/main/functions.php')		     		or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');
(include_once LIBRARY_PATH.'/main/functions_rpi.php')				or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0003');

$token = getConfigValue('config_notification_token');
$lastPush = json_decode(getConfigValue('config_notification_last_push'), true);

if ($lastPush === NULL)
    $lastPush = array();

if (getConfigValue('config_notification_picontrol') == true && (getConfigValue('config_last_update_check')+21600) < time())
{
    $picontrol_update = checkUpdate();
    
    if (!isset($lastPush['picontrol']))
        $lastPush['picontrol'] = array('time' => 0, 'value' => 0, 'notification_iden' => '');
    
    if (!is_array($picontrol_update))
        setConfigValue('config_last_update_check', time());
    else
    {
        if ($lastPush['picontrol']['value'] < $picontrol_update['versioncode']  && $lastPush['picontrol']['time']+21600 < time())
        {
            setConfigValue('config_last_update_check', time()-86400);
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/pushes');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token, 'Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '{"type": "note", "title": "Pi Control | Neue Version", "body": "Pi Control Version '.$picontrol_update['version'].' steht ab sofort zum herunterladen bereit."}');
            curl_setopt($ch, CURLOPT_POST, 1);
            $return = json_decode(curl_exec($ch), true);
            curl_close($ch);
            
            // Reduziere Traffic, da selbst bei Fehler erst wieder nach 21600 Sek. geprüft wird
            $lastPush['picontrol']['time'] = time();
            
            if (!isset($return['error']))
            {
                $lastPush['picontrol']['value'] = $picontrol_update['versioncode'];
                $lastPush['picontrol']['notification_iden'] = $return['iden'];
            }
        }
    }
}

if (getConfigValue('config_notification_cpu_temp') == true)
{
    $temp = rpi_getCoreTemprature();
    
    if (!isset($lastPush['cpu_temp']))
        $lastPush['cpu_temp'] = array('time' => 0, 'value' => 0, 'notification_iden' => '');
    
    if ($temp > getConfigValue('config_notification_cpu_temp_value') && $lastPush['cpu_temp']['value'] < ($temp + 5) && $lastPush['cpu_temp']['time']+300 < time())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/pushes');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"type": "note", "title": "Pi Control | Temperaturüberschreitung", "body": "Dein Pi Control meldet eine erhöhte Temperatur der CPU von '.$temp.'°C."}');
        curl_setopt($ch, CURLOPT_POST, 1);
        $return = json_decode(curl_exec($ch), true);
        curl_close($ch);
        
        if (!isset($return['error']))
        {
            $lastPush['cpu_temp']['time'] = time();
            $lastPush['cpu_temp']['value'] = $temp;
            $lastPush['cpu_temp']['notification_iden'] = $return['iden'];
        }
    }
    elseif (getConfigValue('config_notification_cpu_temp_value') > $temp)
        $lastPush['cpu_temp']['value'] = 0;
}

if (getConfigValue('config_notification_memory') == true)
{
    $memory = rpi_getMemoryInfo();
    $percent = $memory[count($memory)-1]['percent'];
    
    if (!isset($lastPush['memory']))
        $lastPush['memory'] = array('time' => 0, 'value' => 0, 'notification_iden' => '');
    
    if (getConfigValue('config_notification_memory_value') < $percent && $lastPush['memory']['value'] < ((100 - $percent) / 10 + $percent) && $lastPush['memory']['time']+3600 < time())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/pushes');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"type": "note", "title": "Pi Control | Speicherverbrauch", "body": "Dein Pi Control meldet einen Speicherverbrauch von '.$percent.'%."}');
        curl_setopt($ch, CURLOPT_POST, 1);
        $return = json_decode(curl_exec($ch), true);
        curl_close($ch);
        
        if (!isset($return['error']))
        {
            $lastPush['memory']['time'] = time();
            $lastPush['memory']['value'] = $percent;
            $lastPush['memory']['notification_iden'] = $return['iden'];
        }
    }
    elseif (getConfigValue('config_notification_memory_value') > $percent)
        $lastPush['memory']['value'] = 0;
}

setConfigValue('config_notification_last_push', '\''.json_encode($lastPush).'\'');
?>