<?php
// setup.php
(include_once realpath(dirname(__FILE__)).'/../main_config.php');

(include_once LIBRARY_PATH.'/main/rain.tpl.nocache.class.php');
(include_once LIBRARY_PATH.'/main/functions.php');

$errors = array();

if (($set_config_fixed_header = setConfigValue('config_fixed_header', 'true')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2001-'.$set_config_fixed_header;

if (($set_config_notification = setConfigValue('config_notification', 'false')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2002-'.$set_config_notification;

if (($set_config_notification_token  = setConfigValue('config_notification_token', '\'\'')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2003-'.$set_config_notification_token;

if (($set_config_notification_last_push = setConfigValue('config_notification_last_push', '\'\'')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2004-'.$set_config_notification_last_push;

if (($set_config_notification_picontrol = setConfigValue('config_notification_picontrol', 'false')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2005-'.$set_config_notification_picontrol;

if (($set_config_notification_cpu_temp = setConfigValue('config_notification_cpu_temp', 'false')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2006-'.$set_config_notification_cpu_temp;

if (($set_config_notification_cpu_temp_value = setConfigValue('config_notification_cpu_temp_value', '65')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2007-'.$set_config_notification_cpu_temp_value;

if (($set_config_notification_memory = setConfigValue('config_notification_memory', 'false')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2008-'.$set_config_notification_memory;

if (($set_config_notification_memory_value = setConfigValue('config_notification_memory_value', '80')) !== 0)
	$errors[] = 'Konnte Wert nicht in Konfigurationsdatei speichern! Fehlercode: 0x2009-'.$set_config_notification_memory_value;


if (count($errors) > 0)
{
	echo '<strong>Leider gab es Probleme bei dem Update. Bitte schicke mir unter support@willy-tech.de eine E-Mail mit einem Screenshot der Fehlermeldungen. Ich werde dir dann so schnell wie möglich weiterhelfen. Sollte nur die Cron-Konfiguration Probleme machen, kannst du <a href="../../?s=settings&do=update&statusmsg=updated">hier</a> die Installation beenden und den Cron manuell anlegen.<ul>'."\n";
	
	foreach ($errors as $error)
		echo '<li>'.$error.'</li>'."\n";
	
	echo '</ul>';
}
else
{
	unlink('setup.php');
	
	if (!headers_sent($filename, $linenum))
		exit(header('Location: ../../?s=settings&do=update&statusmsg=updated'));
	else
		echo 'Header bereits gesendet. Redirect nicht m&ouml;glich, klicke daher stattdessen <a href="../../?s=settings&do=update&statusmsg=updated">diesen Link</a> an.';
}
?>