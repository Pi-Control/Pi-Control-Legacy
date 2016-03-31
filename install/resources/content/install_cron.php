<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Cron'));

$showInfo = false;

if (isset($_POST['submit'], $_POST['ssh-login']) && $_POST['submit'] != '' && in_array($_POST['ssh-login'], array('password', 'publickey')))
{
	$pType = $_POST['ssh-login'];
	
	if ($pType == 'password')
	{
		if (isset($_POST['port'], $_POST['username'], $_POST['password']) && ($pPort = intval(trim($_POST['port']))) != '' && ($pUsername = trim($_POST['username'])) != '' && ($pPassword = $_POST['password']) != '')
		{
			if (is_numeric($pPort) && $pPort >= 0 && $pPort <= 65535)
			{
				if (setCronToCrontab($pType, $pPort, $pUsername, $pPassword, NULL) === true)
					$showInfo = true;
				else
					$tpl->msg('error', _t('Fehler'), _t('Verbindung zum Raspberry Pi war nicht erfolgreich!<br /><br />Bitte &uuml;berpr&uuml;fe die eingegebenen Daten. Schl&auml;gt ein erneuter Versuch mit korrekten Daten fehl, wende dich bitte unten unter "Feedback" an mich, ich werde dir so schnell wie m&ouml;glich weiterhelfen.'));
			}
			else
				$tpl->msg('error', _t('Fehler'), _t('Ung&uuml;ltiger Port. Der Port muss zwischen 0 und 65535 liegen.'));
		}
		else
			$tpl->msg('error', _t('Fehler'), _t('Bitte alle Felder ausf&uuml;llen!'));
	}
	elseif ($pType == 'publickey')
	{
		if (isset($_POST['port_'], $_POST['username_'], $_POST['privatekey_']) && ($pPort = intval(trim($_POST['port_']))) != '' && ($pUsername = trim($_POST['username_'])) != '' && ($pPrivateKey = $_POST['privatekey_']) != '')
		{
			$pPassword = '';
			
			if (isset($_POST['password_']) && ($pPassword = $_POST['password_']) != '')
			{
			}
			
			if (is_numeric($pPort) && $pPort >= 0 && $pPort <= 65535)
			{
				if (setCronToCrontab($pType, $pPort, $pUsername, $pPassword, $pPrivateKey) === true)
					$showInfo = true;
				else
					$tpl->msg('error', _t('Fehler'), _t('Verbindung zum Raspberry Pi war nicht erfolgreich!<br /><br />Bitte &uuml;berpr&uuml;fe die eingegebenen Daten. Schl&auml;gt ein erneuter Versuch mit korrekten Daten fehl, wende dich bitte unten unter "Feedback" an mich, ich werde dir so schnell wie m&ouml;glich weiterhelfen.'));
			}
			else
				$tpl->msg('error', _t('Fehler'), _t('Ung&uuml;ltiger Port. Der Port muss zwischen 0 und 65535 liegen.'));
		}
		else
			$tpl->msg('error', _t('Fehler'), _t('Bitte alle Felder ausf&uuml;llen!'));
	}
}

if ($showInfo === true)
	$tpl->draw('install_cron_info');
else
{
	$tpl->assign('port', (isset($_POST['port'])) ? $_POST['port'] : 22);
	$tpl->assign('username', (isset($_POST['username'])) ? $_POST['username'] : '');

	$tpl->draw('install_cron');
}
?>