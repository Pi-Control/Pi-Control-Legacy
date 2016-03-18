<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('SSH-Login'));

// Anmelden
if (isset($_POST['submit'], $_POST['ssh-login']) && $_POST['submit'] != '' && in_array($_POST['ssh-login'], array('password', 'publickey')))
{
	$pType = $_POST['ssh-login'];
	
	if ($pType == 'password')
	{
		if (isset($_POST['port'], $_POST['username'], $_POST['password']) && ($pPort = intval(trim($_POST['port']))) != '' && ($pUsername = trim($_POST['username'])) != '' && ($pPassword = $_POST['password']) != '')
		{
			if (is_numeric($pPort) && $pPort >= 0 && $pPort <= 65535)
			{
				if ($tpl->setSSHInfo($pType, $pPort, $pUsername, $pPassword, NULL) === true)
				{
					if ($tpl->getSSHResource() !== false)
						$tpl->msg('success', '', _t('Verbindung zum Raspberry Pi wurde hergestellt.'));
					else
						$tpl->msg('error', '', _t('Verbindung zum Raspberry Pi war nicht erfolgreich!<br /><br />Bitte &uuml;berpr&uuml;fe die eingegebenen Daten. Schl&auml;gt ein erneuter Versuch mit korrekten Daten fehl, wende dich bitte unten unter "Feedback" an mich, ich werde dir so schnell wie m&ouml;glich weiterhelfen.'));
				}
				else
					$tpl->msg('error', '', _t('Fehler beim Speichern der Daten!'));
			}
			else
				$tpl->msg('error', '', _t('Ung&uuml;ltiger Port. Der Port muss zwischen 0 und 65535 liegen.'));
		}
		else
			$tpl->msg('error', '', _t('Bitte alle Felder ausf&uuml;llen.'));
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
				if ($tpl->setSSHInfo($pType, $pPort, $pUsername, $pPassword, $pPrivateKey) === true)
				{
					if ($tpl->getSSHResource() !== false)
						$tpl->msg('success', '', _t('Verbindung zum Raspberry Pi wurde hergestellt.'));
					else
						$tpl->msg('error', '', _t('Verbindung zum Raspberry Pi war nicht erfolgreich!<br /><br />Bitte &uuml;berpr&uuml;fe die eingegebenen Daten. Schl&auml;gt ein erneuter Versuch mit korrekten Daten fehl, wende dich bitte unten unter "Feedback" an mich, ich werde dir so schnell wie m&ouml;glich weiterhelfen.'));
				}
				else
					$tpl->msg('error', '', _t('Fehler beim Speichern der Daten!'));
			}
			else
				$tpl->msg('error', '', _t('Ung&uuml;ltiger Port. Der Port muss zwischen 0 und 65535 liegen.'));
		}
		else
			$tpl->msg('error', '', _t('Bitte alle Felder ausf&uuml;llen.'));
	}
}

// Abmelden
if (isset($_GET['logout']))
{
	if ($tpl->logoutSSH() === true)
		$tpl->msg('success', '', _t('Erfolgreich abgemeldet.'));
	else
		$tpl->msg('error', '', _t('Beim Abmelden ist ein Fehler aufgetreten!'));
}

$SSHInfo = $tpl->getSSHInfo();

if (!is_array($SSHInfo))
	$tpl->msg('error', '', _t('Konnte SSH-Informationen nicht abrufen.'), false);

$tpl->assign('ssh_info', $SSHInfo);
$tpl->assign('logged_in', ($tpl->getSSHResource() !== false));

$tpl->draw('ssh_login');
?>