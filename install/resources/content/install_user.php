<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'cache/cache.function.php') or die($error_code['0x0007']);
$tpl->setHeaderTitle(_t('Benutzer'));

$dataUser = json_decode(readFromFile('user'), true);

if (isset($dataUser['username'], $dataUser['password']) && $dataUser['username'] != '' && $dataUser['password'] != '')
{
	$tpl->msg('info', _t('Benutzer bereits erstellt'), _t('Es wurde bereits ein Benutzer f&uuml;r das Pi Control erstellt. Du kannst diesen <a href="%s">Schritt &uuml;berspringen</a> oder einfach den aktuellen Benutzer &uuml;berschreiben, indem du hier einen neuen Benutzer erstellst.', '?s=install_cron'), false);
}

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (isset($_POST['username'], $_POST['password'], $_POST['password2']) && ($pUsername = trim($_POST['username'])) != '' && ($pPassword = $_POST['password']) != '' && ($pPassword2 = $_POST['password2']) != '')
	{
		if (preg_match('/^[a-z][a-z0-9\-_]{1,31}$/i', $pUsername) === 1)
		{
			$lowerUsername = strtolower($pUsername);
			if (preg_match('/^[a-z0-9_\-\+\*\/\#\.]{4,64}$/i', $pPassword) === 1)
			{
				if ($pPassword == $pPassword2)
				{
					if (($return = writeToFile('user', json_encode(array('username' => $pUsername, 'password' => password_hash($pPassword, PASSWORD_BCRYPT))))) === 0)
						$tpl->redirect('?s=install_cron');
					else
						$tpl->msg('error', '', _t('Es gab ein Fehler w&auml;hrend der Dateioperation! Fehlercode: %s', $return));
				}
				else
					$tpl->msg('error', '', _t('Die angegebenen Passw&ouml;rter stimmen nicht &uuml;berein!'));
			}
			else
				$tpl->msg('error', '', _t('Leider ist das Passwort ung&uuml;ltig! Das Passwort muss aus 4 bis 64 Zeichen bestehen und darf nur folgende Zeichen beinhalten: A-Z 0-9 - _ + * / # .'));
		}
		else
			$tpl->msg('error', '', _t('Leider ist der Benutzername ung&uuml;ltig! Der Benutzername muss aus 2 bis 32 Zeichen bestehen. Das erste Zeichen muss ein Buchstabe sein und es sind nur folgende Zeichen erlaubt: A-Z 0-9 - _'));
	}
	else
		$tpl->msg('error', '', _t('Bitte alle Felder ausf&uuml;llen!'));
}

$tpl->draw('install_user');
?>