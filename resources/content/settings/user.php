<?php
(include_once LIBRARY_PATH.'/user/user.function.php') or die($error_code['0x0006']);
$tpl->setHeaderTitle(_t('Einstellungen zum Benutzer'));

$showOverview = true;

if (isset($_GET['add']) && $_GET['add'] == '')
{
	if (isset($_POST['submit']) && $_POST['submit'] != '')
	{
		if (isset($_POST['username'], $_POST['password'], $_POST['password2']) && ($pUsername = trim($_POST['username'])) != '' && ($pPassword = $_POST['password']) != '' && ($pPassword2 = $_POST['password2']) != '')
		{
			if (preg_match('/^[a-z][a-z0-9\-_]{1,31}$/i', $pUsername) === 1)
			{
				$lowerUsername = strtolower($pUsername);
				if ($tpl->getConfig('user:user_'.$lowerUsername.'.username', '') == '')
				{
					if (preg_match('/^[a-z0-9_\-\+\*\/\#\.]{4,64}$/i', $pPassword) === 1)
					{
						if ($pPassword == $pPassword2)
						{
							$tpl->setConfig('user:user_'.$lowerUsername.'.username', $pUsername);
							$tpl->setConfig('user:user_'.$lowerUsername.'.created', time());
							$tpl->setConfig('user:user_'.$lowerUsername.'.password', md5($pPassword));
							$tpl->setConfig('user:user_'.$lowerUsername.'.last_login', 0);
							$tpl->msg('success', '', 'Der Benutzer "'.$pUsername.'" wurder erfolgreich angelegt.');
						}
						else
							$tpl->msg('error', '', 'Die angegebenen Passw&ouml;rter stimmen nicht &uuml;berein!');
					}
					else
						$tpl->msg('error', '', 'Leider ist das Passwort ung&uuml;ltig! Das Passwort muss aus 4 bis 64 Zeichen bestehen und darf nur folgende Zeichen beinhalten: A-Z 0-9 - _ + * / # .');
				}
				else
					$tpl->msg('error', '', 'Leider ist der Benutzername bereits vergeben! Bitte w&auml;hle einen anderen.');
			}
			else
				$tpl->msg('error', '', 'Leider ist der Benutzername ung&uuml;ltig! Der Benutzername muss aus 2 bis 32 Zeichen bestehen. Das erste Zeichen muss ein Buchstabe sein und es sind nur folgende Zeichen erlaubt: A-Z 0-9 - _');
		}
		else
			$tpl->msg('error', '', 'Bitte alle Felder ausf&uuml;llen!');
	}
	
	$showOverview = false;
	$tpl->draw('settings/user_add');
}
elseif (isset($_GET['delete']) && $_GET['delete'] != '')
{
	$lowerUsername = $_GET['delete'];
	$username = $tpl->getConfig('user:user_'.$lowerUsername.'.username', '');
	
	if ($username != '')
	{
		$showDelete = true;
		
		if (isset($_POST['submit']))
		{
			if (isset($_POST['password']) && ($pPassword = $_POST['password']) != '')
			{
				$password = $tpl->getConfig('user:user_'.$lowerUsername.'.password', '');
				
				if ($password == md5($pPassword))
				{
					removeConfig('user:user_'.$lowerUsername);
					$loggedInUsers = getConfig('login');
					
					foreach ($loggedInUsers as $key => $user)
					{
						if ($user['username'] == $lowerUsername)
							removeConfig('login:'.$key);
					}
					
					$showDelete = false;
					$tpl->msg('success', '', 'Der Benutzer wurde erfolgreich gel&ouml;scht!');
				}
				else
					$tpl->msg('error', '', 'Das Passwort ist nicht korrekt!');
			}
			else
				$tpl->msg('error', '', 'Bitte alle Felder ausf&uuml;llen!');
		}
		
		if ($showDelete === true)
		{
			$tpl->assign('lowerUsername', $lowerUsername);
			$tpl->assign('username', $username);
			
			$showOverview = false;
			$tpl->draw('settings/user_delete');
		}
	}
	else
		$tpl->msg('error', '', 'Leider existiert der Benutzer nicht!');
}
elseif (isset($_GET['edit']) && $_GET['edit'] != '')
{
	$lowerUsername = $_GET['edit'];
	$username = $tpl->getConfig('user:user_'.$lowerUsername.'.username', '');
	
	if ($username != '')
	{
		if (isset($_POST['submit']))
		{
			if (isset($_POST['passwordOld'], $_POST['passwordNew'], $_POST['passwordNew2']) && ($pPasswordOld = $_POST['passwordOld']) != '' && ($pPasswordNew = $_POST['passwordNew']) != '' && ($pPasswordNew2 = $_POST['passwordNew2']) != '')
			{
				if (preg_match('/^[a-z0-9_\-\+\*\/\#\.]{4,64}$/i', $pPasswordNew) === 1)
				{
					$passwordOld = $tpl->getConfig('user:user_'.$lowerUsername.'.password', '');
					
					if ($passwordOld == md5($pPasswordOld))
					{
						if ($pPasswordNew == $pPasswordNew2)
						{
							$tpl->setConfig('user:user_'.$lowerUsername.'.password', md5($pPasswordNew));
							$tpl->msg('success', '', 'Der Benutzer "'.$username.'" wurde erfolgreich bearbeitet und gespeichert.');
						}
						else
							$tpl->msg('error', '', 'Das neue Passwort stimmt nicht mit der Wiederholung &uuml;berein!');
					}
					else
						$tpl->msg('error', '', 'Das alte Passwort ist nicht korrekt!');
				}
				else
					$tpl->msg('error', '', 'Leider ist das Passwort ung&uuml;ltig! Das Passwort muss aus 4 bis 64 Zeichen bestehen und darf nur folgende Zeichen beinhalten: A-Z 0-9 - _ + * / # .');
			}
			else
				$tpl->msg('error', '', 'Bitte alle Felder ausf&uuml;llen!');
		}
		
		$tpl->assign('lowerUsername', $lowerUsername);
		$tpl->assign('username', $username);
		
		$showOverview = false;
		$tpl->draw('settings/user_edit');
	}
	else
		$tpl->msg('error', '', 'Leider existiert der Benutzer nicht!');
}

if ($showOverview === true)
{
	if (isset($_POST['logout']) && $_POST['logout'] != '' && strlen($_POST['logout']) == 16)
	{
		removeConfig('login:token_'.$_POST['logout']);
		$tpl->msg('success', '', 'Der Benutzer wurde erfolgreich abgemeldet.');
	}

	$allUsers = getConfig('user');
	$loggedInUsers = getConfig('login');

	array_walk($loggedInUsers, 'loggedInUsers', $allUsers);

	$loggedInUsers = array_sort($loggedInUsers, 'created', SORT_DESC);

	$tpl->assign('allUsers', $allUsers);
	$tpl->assign('loggedInUsers', $loggedInUsers);

	$tpl->draw('settings/user');
}
?>