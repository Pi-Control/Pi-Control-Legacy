<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Benachrichtigung'));

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (isset($_POST['token']) && ($token = trim($_POST['token'])) != '' && ((isset($_POST['event-pi-control-version']) && $_POST['event-pi-control-version'] == 'checked') || (isset($_POST['event-cpu-temperature'], $_POST['event-cpu-temperature-maximum']) && $_POST['event-cpu-temperature'] == 'checked') || (isset($_POST['event-memory-used'], $_POST['event-memory-used-text']) && $_POST['event-memory-used'] == 'checked')))
	{
		$cpu_temperature_maximum = trim($_POST['event-cpu-temperature-maximum']);
		$memory_used_text = trim($_POST['event-memory-used-text']);
		
		if (strlen($token) >= 32 && strlen($token) <= 46)
			setConfig('main:notificationPB.token', $token);
		else
			$tpl->msg('error', '', 'Leider ist der angegebene Zugangstoken ung&uuml;ltig!', true, 10);
		
		if ($cpu_temperature_maximum != '' && $cpu_temperature_maximum >= 40 && $cpu_temperature_maximum <= 90)
			setConfig('main:notificationPB.cpuTemperatureMaximum', $cpu_temperature_maximum);
		else
			$tpl->msg('error', '', 'Leider ist die angegebene Temperatur ung&uuml;ltig!', true, 11);
		
		if ($memory_used_text != '' && $memory_used_text >= 1 && $memory_used_text <= 100)
			setConfig('main:notificationPB.memoryUsedLimit', $memory_used_text);
		else
			$tpl->msg('error', '', 'Leider ist der angegebene Prozentsatz ung&uuml;ltig!', true, 12);
		
		if ($tpl->msgExists(10) === false)
			setConfig('main:notificationPB.picontrolVersionEnabled', (isset($_POST['event-pi-control-version'])) ? 'true' : 'false');
		
		if ($tpl->msgExists(11) === false)
			setConfig('main:notificationPB.cpuTemperatureEnabled', (isset($_POST['event-cpu-temperature'])) ? 'true' : 'false');
		
		if ($tpl->msgExists(12) === false)
			setConfig('main:notificationPB.memoryUsedEnabled', (isset($_POST['event-memory-used'])) ? 'true' : 'false');
		
		if ($tpl->msgExists(10) === false && $tpl->msgExists(11) === false && $tpl->msgExists(12) === false)
			$tpl->msg('success', '', 'Die Einstellungen wurden erfolgreich gespeichert.');
	}
	elseif (isset($_POST['token']) && ($token = trim($_POST['token'])) == '')
	{
		setConfig('main:notificationPB.token', '');
		$tpl->msg('success', '', 'Der Token wurde erfolgreich entfernt.');
	}
	else
		$tpl->msg('error', '', 'Bitte f&uuml;lle alle n&ouml;tigen Felder aus und w&auml;hle mindestens eine der Aktionen!');
}

$token = getConfig('main:notificationPB.token', '');
if ($token != '')
{
	if (isset($_GET['test_notification']))
    {
		$curl = new cURL('https://api.pushbullet.com/v2/pushes', HTTP_POST);
		$curl->addHeader(array('Authorization: Bearer '.$token, 'Content-Type: application/json'));
		$curl->setParameterRaw(json_encode(array('type' => 'note', 'title' => 'Pi Control', 'body' => 'Dein Pi Control hat dir eine Testbenachrichtigung gesendet.')));
		$curl->execute();
		
		if ($curl->getStatusCode() != 200)
			$tpl->msg('error', 'Verbindungsfehler', 'Bei der Verbindung zu Pushbullet ist ein unerwarteter Fehler aufgetreten. Fehlercode: '.$curl->getStatusCode());
    }
	
	$curl = new cURL('https://api.pushbullet.com/v2/users/me');
	$curl->addHeader(array('Authorization: Bearer '.$token));
	$curl->execute();
	
	if (in_array($curl->getStatusCode(), array(200, 400, 401, 403, 404, 429)))
	{
		if ($curl->getResult($dataMe) == JSON_ERROR_NONE)
		{
			if (isset($dataMe['error']))
				$tpl->msg('error', 'Pushbullet', 'Pushbullet meldet einen Fehler mit einer Anfrage: '.$dataMe['error']['message']);
			else
			{
				$curl = new cURL('https://api.pushbullet.com/v2/devices');
				$curl->addHeader(array('Authorization: Bearer '.$token));
				$curl->execute();
				
				if (in_array($curl->getStatusCode(), array(200, 400, 401, 403, 404, 429)))
				{
					if ($curl->getResult($dataDevices) == JSON_ERROR_NONE)
					{
						if (isset($dataDevices['error']))
							$tpl->msg('error', 'Pushbullet', 'Pushbullet meldet einen Fehler mit einer Anfrage: '.$dataDevices['error']['message']);
					}
				}
				else
					$tpl->msg('error', 'Verbindungsfehler', 'Bei der Verbindung zu Pushbullet ist ein unerwarteter Fehler aufgetreten. Fehlercode: '.$curl->getStatusCode());
			}
		}
	}
	else
		$tpl->msg('error', 'Verbindungsfehler', 'Bei der Verbindung zu Pushbullet ist ein unerwarteter Fehler aufgetreten. Fehlercode: '.$curl->getStatusCode());
}

$tpl->assign('activation', false);
$tpl->assign('token', $token);
$tpl->assign('me', (isset($dataMe)) ? $dataMe : '');
$tpl->assign('devices', (isset($dataDevices)) ? $dataDevices : '');
$tpl->assign('pi-control-enabled', (getConfig('main:notificationPB.picontrolVersionEnabled', 'false') == 'true') ? true : false);
$tpl->assign('cpu-temperature-enabled', (getConfig('main:notificationPB.cpuTemperatureEnabled', 'false') == 'true') ? true : false);
$tpl->assign('cpu-temperature-maximum', getConfig('main:notificationPB.cpuTemperatureMaximum', 65));
$tpl->assign('memory-used-enabled', (getConfig('main:notificationPB.memoryUsedEnabled', 'false') == 'true') ? true : false);
$tpl->assign('memory-used-limit', getConfig('main:notificationPB.memoryUsedLimit', 80));

$tpl->draw('settings/notification');
?>