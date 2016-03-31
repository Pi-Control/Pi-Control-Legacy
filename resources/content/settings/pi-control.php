<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'pi-control/pi-control.function.php') or die('Error: 0x0010');
$tpl->setHeaderTitle(_t('Einstellungen - Pi Control'));

if (isset($_POST['submit-main']) && $_POST['submit-main'] != '')
{
	if (isset($_POST['theme-color']) && in_array($_POST['theme-color'], array('red', 'pink', 'purple', 'deepPurple', 'indigo', 'blue', 'lightBlue', 'cyan', 'teal', 'green', 'lightGreen', 'lime', 'yellow', 'amber', 'orange', 'deepOrange', 'brown', 'grey', 'blueGrey')) === true)
	{
		$tpl->setConfig('main:theme.color', $_POST['theme-color']);
		$tpl->setConfig('main:theme.colorChanged', time());
		$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'), true, 10);
	}
	
	if (isset($_POST['pi-control-language']) && in_array($_POST['pi-control-language'], array('de', 'en')) === true)
	{
		setConfig('init:language', $_POST['pi-control-language']);
		$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'), true, 10);
	}
	
	if (isset($_POST['external-access']) && $_POST['external-access'] == 'checked')
	{
		$tpl->setConfig('main:access.external', 'true');
		$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'), true, 10);
	}
	else
	{
		$tpl->setConfig('main:access.external', 'false');
		$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'), true, 10);
	}
	
	if (isset($_POST['pi-control-label']) && ($pLabel = trim($_POST['pi-control-label'])) != '')
	{
		if (preg_match('/^[a-z][a-z0-9_\-\+\/\.\(\)\[\] ]{2,32}$/i', $pLabel) === 1)
		{
			$tpl->setConfig('main:main.label', $pLabel);
			$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'), true, 10);
		}
		else
			$tpl->msg('error', _t('Fehler'), _t('Leider ist die Bezeichnung ung&uuml;ltig! Die Bezeichnung muss aus 2 bis 32 Zeichen bestehen. Das erste Zeichen muss ein Buchstabe sein und es sind nur folgende Zeichen erlaubt: A-Z 0-9 _ - + / . ( ) [ ] "Leerzeichen"'), true, 10);
	}
	else
		$tpl->msg('error', _t('Fehler'), _t('Bitte vergebe f&uuml;r dein Pi Control eine Bezeichnung!'), true, 10);
}
elseif (isset($_POST['submit-temperature']) && $_POST['submit-temperature'] != '')
{
	$cron = new Cron;
	$cron->setName('coretemp_monitoring');
	
	if (isset($_POST['temperature-maximum']) && $_POST['temperature-maximum'] >= 40 && $_POST['temperature-maximum'] <= 90 && ((isset($_POST['temperature-action-email']) && $_POST['temperature-action-email'] == 'checked') || (isset($_POST['temperature-action-shell']) && $_POST['temperature-action-shell'] == 'checked')))
	{
		setConfig('main:monitoringCpuTemp.maximum', $_POST['temperature-maximum']);
		setConfig('main:monitoringCpuTemp.emailEnabled', isset($_POST['temperature-action-email']) ? 'true' : 'false');
		setConfig('main:monitoringCpuTemp.shellEnabled', isset($_POST['temperature-action-shell']) ? 'true' : 'false');
		
		$pActionEmail = trim($_POST['temperature-action-email-text']);
		$pActionShell = trim($_POST['temperature-action-shell-text']);
		
		if (isset($_POST['temperature-action-email']))
		{
			if (!filter_var($pActionEmail, FILTER_VALIDATE_EMAIL) || !strlen($pActionEmail) >= 6)
				$tpl->msg('error', _t('Fehler'), _t('Bitte gebe eine g&uuml;ltige E-Mailadresse an.'), true, 10);
		}
		
		if ($tpl->msgExists(10) === false)
			setConfig('main:monitoringCpuTemp.email', $pActionEmail);
		
		if (isset($_POST['temperature-action-shell']))
		{
			if ($pActionShell == '')
				$tpl->msg('error', _t('Fehler'), _t('Bitte gebe einen g&uuml;ltigen Shell Befehl an.'), true, 11);
		}
		
		if ($tpl->msgExists(11) === false)
			setConfig('main:monitoringCpuTemp.shell', base64_encode($pActionShell));
		
		if (getConfig('main:monitoringCpuTemp.id', '') == '')
		{
			$uniqid = generateUniqId();
			setConfig('main:monitoringCpuTemp.id', $uniqid);
		}
		
		if (isset($_POST['temperature-action-email']) && $tpl->msgExists(10) === false)
			checkTemperatureMonitoringEmailStatus();
	}
	else
		$tpl->msg('error', _t('Fehler'), _t('Bitte w&auml;hle mindestens eine Aktion.'), true, 10);
	
	if (isset($_POST['temperature-activation']) && $_POST['temperature-activation'] == 'checked')
	{
		if ($cron->isExists() === false)
		{
			$cron->setInterval(1);
			$cron->setSource(TEMPLATES2_PATH.'coretemp_monitoring.tmp.php');
			if ($cron->save() === true)
				$tpl->msg('success', _t('Temperatur&uuml;berwachung aktiviert'), _t('Die Temperatur&uuml;berwachung wurde aktiviert.'));
			else
				$tpl->msg('error', _t('Fehler'), _t('Konnte die Temperatur&uuml;berwachung nicht aktivieren!'));
		}
	}
	else
	{
		if ($cron->isExists() === true)
		{
			//$cron->readFile();
			$cron->setInterval($cron->getInterval());
			if ($cron->delete() === true)
				$tpl->msg('success', _t('Temperatur&uuml;berwachung deaktiviert'), _t('Die Temperatur&uuml;berwachung wurde deaktiviert.'));
			else
				$tpl->msg('error', _t('Fehler'), _t('Konnte die Temperatur&uuml;berwachung nicht deaktivieren!'));
		}
	}
	
	if ($tpl->msgExists(10) === false && $tpl->msgExists(11) === false)
		$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'));
}

if (isset($_POST['submit-temperature-confirmation']) && $_POST['submit-temperature-confirmation'] != '')
{
	$id = getConfig('main:monitoringCpuTemp.id', '');
	$code = getConfig('main:monitoringCpuTemp.code', '');
	$email = getConfig('main:monitoringCpuTemp.email', '');
	$label = getConfig('main:main.label', '');
	
	if ($id == '' || $email == '' || $label == '')
		$tpl->msg('error', _t('Fehler'), _t('Leider ist ein Fehler aufgetreten. Bitte wiederhole die Vergabe der Bezeichnung und der E-Mailadresse.'));
	else
	{
		$fields = array('type' => 'add', 'id' => $id, 'email' => $email, 'label' => $label, 'referer' => $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'lang' => $globalLanguage);
		
		$data = NULL;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $config['url']['temperatureMonitoring']);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POST, count($fields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		do
		{
			if (($data = curl_exec($curl)) === false)
			{
				$info = curl_getinfo($curl);
				$tpl->msg('error', _t('Verbindungsfehler'), _t('Bei der Verbindung zum Server ist ein unerwarteter Fehler aufgetreten. Fehlercode: %d (%s)', $info['http_code'], curl_error($curl)));
				break;
			}
			else
			{
				$info = curl_getinfo($curl);
				
				if ($info['http_code'] == 404)
				{
					$tpl->msg('error', _t('Verbindungsfehler'), _t('Leider konnte keine Verbindung zum Server hergestellt werden, da dieser momentan vermutlich nicht erreichbar ist. Fehlercode: %d', $info['http_code']));
					break;
				}
				elseif ($info['http_code'] != 200)
				{
					$tpl->msg('error', _t('Verbindungsfehler'), _t('Bei der Verbindung zum Server ist ein unerwarteter Fehler aufgetreten. Fehlercode: %d', $info['http_code']));
					break;
				}
				
				if ($data == '')
				{
					$tpl->msg('error', _t('Serverfehler'), _t('Bei der Verbindung zum Server ist ein Fehler aufgetreten. Der Server sendet eine leere Antwort.'));
					break;
				}
				
				// Verarbeite Datenstring
				$json = json_decode($data, true);
				
				if (json_last_error() != JSON_ERROR_NONE && (!isset($json['existing'], $json['sent']) || !isset($json['type'], $json['title'], $json['msg'], $json['skip'])))
				{
					$tpl->msg('error', _t('Verarbeitungsfehler'), _t('Bei der Verbindung zum Server ist ein Fehler aufgetreten. Der Server sendet eine fehlerhafte Antwort.'));
					break;
				}
				
				if (isset($json['type'], $json['title'], $json['msg'], $json['skip']))
				{
					$tpl->msg($json['type'], $json['title'], $json['msg'], true, 10);
					
					if ($json['skip'] === true)
						break;
				}
				
				// Antwort in Ordnung
				$tpl->msg('success', _t('E-Mail gesendet'), _t('Eine E-Mail mit einem Best&auml;tigungslink wurde an <strong>%s</strong> versandt. In der E-Mail ist ein Link, der angeklickt bzw. ge&ouml;ffnet werden muss. Sollte die E-Mail nach sp&auml;testens 10 Minuten nicht angekommen sein, schaue in deinem Spam-Ordner nach. Ansonsten wiederhole den Vorgang.', $email));
			}
		}
		while (false);
		
		curl_close($curl);
	}
}

if (isset($_GET['mail_check']) && $_GET['mail_check'] == '')
{
	checkTemperatureMonitoringEmailCode();
}

$cron = new Cron;
$cron->setName('coretemp_monitoring');

$tpl->assign('main-theme-color', getConfig('main:theme.color', 'blue'));
$tpl->assign('main-pi-control-label', getConfig('main:main.label', 'Raspberry Pi'));
$tpl->assign('main-pi-control-language', getConfig('init:language', 'de'));
$tpl->assign('main-external-access', getConfig('main:access.external', 'false'));
$tpl->assign('temperature-activation', $cron->isExists());
$tpl->assign('temperature-maximum', getConfig('main:monitoringCpuTemp.maximum', 60));
$tpl->assign('temperature-action-email', (getConfig('main:monitoringCpuTemp.emailEnabled', 'false') == 'true') ? true : false);
$tpl->assign('temperature-action-email-text', getConfig('main:monitoringCpuTemp.email', ''));
$tpl->assign('temperature-action-email-status', (getConfig('main:monitoringCpuTemp.code', '') == '') ? 0 : 1);
$tpl->assign('temperature-action-shell', (getConfig('main:monitoringCpuTemp.shellEnabled', 'false') == 'true') ? true : false);
$tpl->assign('temperature-action-shell-text', htmlentities(base64_decode(getConfig('main:monitoringCpuTemp.shell', ''))));
$tpl->assign('temperature-last-execution', (getConfig('cron:execution.monitoringCpuTemp', 0)-time()+3600 > 0) ? getDateFormat(getConfig('cron:execution.monitoringCpuTemp', 0)-time()+3600) : '');
$tpl->assign('whoami', exec('whoami'));

$tpl->draw('settings/pi-control');
?>