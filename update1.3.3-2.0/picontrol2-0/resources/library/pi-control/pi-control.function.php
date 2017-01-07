<?php
if (!defined('PICONTROL')) exit();

function checkTemperatureMonitoringEmailStatus()
{
	global $tpl, $config;
	
	$id = getConfig('main:monitoringCpuTemp.id', '');
	$code = getConfig('main:monitoringCpuTemp.code', '');
	$email = getConfig('main:monitoringCpuTemp.email', '');
	
	$data = NULL;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $config['url']['temperatureMonitoring'].'?'.http_build_query(array('id' => $id, 'code' => $code, 'email' => $email)));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	do
	{
		if (($data = curl_exec($curl)) === false)
		{
			$info = curl_getinfo($curl);
			$tpl->msg('error', _t('Verbindungsfehler'), _t('Bei der Verbindung zum Server ist ein unerwarteter Fehler aufgetreten. Fehlercode: %d (%s)', $info['http_code'], curl_error($curl)), true, 12);
			break;
		}
		else
		{
			$info = curl_getinfo($curl);
			
			if ($info['http_code'] == 404)
			{
				$tpl->msg('error', _t('Verbindungsfehler'), _t('Leider konnte keine Verbindung zum Server hergestellt werden, da dieser momentan vermutlich nicht erreichbar ist. Fehlercode: %d', $info['http_code']), true, 12);
				break;
			}
			elseif ($info['http_code'] != 200)
			{
				$tpl->msg('error', _t('Verbindungsfehler'), _t('Bei der Verbindung zum Server ist ein unerwarteter Fehler aufgetreten. Fehlercode: %d', $info['http_code']), true, 12);
				break;
			}
			
			if ($data == '')
			{
				$tpl->msg('error', _t('Serverfehler'), _t('Bei der Verbindung zum Server ist ein Fehler aufgetreten. Der Server sendet eine leere Antwort.'), true, 12);
				break;
			}
			
			// Verarbeite Datenstring
			$json = json_decode($data, true);
			
			if (json_last_error() != JSON_ERROR_NONE || !isset($json['existing'], $json['email'], $json['code']))
			{
				$tpl->msg('error', _t('Verarbeitungsfehler'), _t('Bei der Verbindung zum Server ist ein Fehler aufgetreten. Der Server sendet eine fehlerhafte Antwort.'), true, 12);
				break;
			}
			
			// Antwort in Ordnung
			if ($json['existing'] == false || $json['email'] == false || $json['code'] == false)
				setConfig('main:monitoringCpuTemp.code', '');
		}
	}
	while (false);
	
	curl_close($curl);
}

function checkTemperatureMonitoringEmailCode()
{
	global $tpl, $config;
	
	$id = getConfig('main:monitoringCpuTemp.id', '');
	$email = getConfig('main:monitoringCpuTemp.email', '');
	
	$data = NULL;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $config['url']['temperatureMonitoring'].'?'.http_build_query(array('id' => $id, 'code' => true, 'email' => $email)));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	do
	{
		if (($data = curl_exec($curl)) === false)
		{
			$info = curl_getinfo($curl);
			$tpl->msg('error', _t('Verbindungsfehler'), _t('Bei der Verbindung zum Server ist ein unerwarteter Fehler aufgetreten. Fehlercode: %d (%s)', $info['http_code'], curl_error($curl)), true, 12);
			break;
		}
		else
		{
			$info = curl_getinfo($curl);
			
			if ($info['http_code'] == 404)
			{
				$tpl->msg('error', _t('Verbindungsfehler'), _t('Leider konnte keine Verbindung zum Server hergestellt werden, da dieser momentan vermutlich nicht erreichbar ist. Fehlercode: %d', $info['http_code']), true, 12);
				break;
			}
			elseif ($info['http_code'] != 200)
			{
				$tpl->msg('error', _t('Verbindungsfehler'), _t('Bei der Verbindung zum Server ist ein unerwarteter Fehler aufgetreten. Fehlercode: %d', $info['http_code']), true, 12);
				break;
			}
			
			if ($data == '')
			{
				$tpl->msg('error', _t('Serverfehler'), _t('Bei der Verbindung zum Server ist ein Fehler aufgetreten. Der Server sendet eine leere Antwort.'), true, 12);
				break;
			}
			
			// Verarbeite Datenstring
			$json = json_decode($data, true);
			
			if (json_last_error() != JSON_ERROR_NONE || !isset($json['existing'], $json['email'], $json['code']))
			{
				$tpl->msg('error', _t('Verarbeitungsfehler'), _t('Bei der Verbindung zum Server ist ein Fehler aufgetreten. Der Server sendet eine fehlerhafte Antwort.'), true, 12);
				break;
			}
			
			// Antwort in Ordnung
			if ($json['code'] != false && strlen($json['code']) == 16)
			{
				setConfig('main:monitoringCpuTemp.code', $json['code']);
				$tpl->msg('success', _t('E-Mail best&auml;tigt'), _t('Deine E-Mail wurde erfolgreich best&auml;tigt.'));
			}
			else
				$tpl->msg('error', _t('Verarbeitungsfehler'), _t('Der Server konnte zugeh&ouml;rige Daten nicht finden. Versichere, dass du die E-Mail best&auml;tigt hast.'), true, 12);
		}
	}
	while (false);
	
	curl_close($curl);
}
?>