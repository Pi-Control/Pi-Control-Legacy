<?php
$tpl->setHeaderTitle('SSH-Login');

// Anmelden
if (isset($_POST['submit']))
{
	if (isset($_POST['port'], $_POST['username'], $_POST['password']) && $_POST['port'] != '' && $_POST['username'] != '' && $_POST['password'] != '')
	{
		if (is_numeric($_POST['port']) && $_POST['port'] >= 0 && $_POST['port'] <= 65535)
		{
			$ssh = ssh2_connect('127.0.0.1', $_POST['port']);
			$ssh_auth = ssh2_auth_password($ssh, trim($_POST['username']), $_POST['password']);
			if ($ssh_auth === true)
			{
				if ($tpl->setSSHInfo(intval(trim($_POST['port'])), trim($_POST['username']), trim($_POST['password']), (isset($_POST['save_passwd']) && $_POST['save_passwd'] == 'checked') ? true : false) === true)
					$tpl->msg('success', '', _t('Verbindung zum Raspberry Pi wurde hergestellt.'));
				else
					$tpl->msg('error', '', _t('Fehler beim Speichern der Daten!'));
			}
			else
				$tpl->msg('error', '', _t('Verbindung zum Raspberry Pi war nicht erfolgreich!<br /><br />Bitte &uuml;berpr&uuml;fe die eingegebenen Daten. Schl&auml;gt ein erneuter Versuch mit korrekten Daten fehl, wende dich bitte unten unter "Feedback" an mich, ich werde dir so schnell wie m&ouml;glich weiterhelfen.'));
		}
		else
			$tpl->msg('error', '', _t('Ung&uuml;ltiger Port. Der Port muss zwischen 0 und 65535 liegen.'));
	}
	else
		$tpl->msg('error', '', _t('Bitte alle Felder ausf&uuml;llen.'));
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
$tpl->assign('logged_in', is_array($tpl->executeSSH('ls', true, 0)));

$tpl->draw('ssh_login');
?>