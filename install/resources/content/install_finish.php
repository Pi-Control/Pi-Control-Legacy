<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Abschließen'));

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	$dataUser = json_decode(readFromFile('user'), true);
	
	if (isset($dataUser['username'], $dataUser['password']) && $dataUser['username'] != '' && $dataUser['password'] != '')
	{
		if (getConfig('user:user_'.strtolower($dataUser['username']).'.username', '', PICONTROL_PATH.'resources/config/') == '')
		{
			setConfig('user:user_'.strtolower($dataUser['username']).'.username', $dataUser['username'], PICONTROL_PATH.'resources/config/');
			setConfig('user:user_'.strtolower($dataUser['username']).'.created', time(), PICONTROL_PATH.'resources/config/');
			setConfig('user:user_'.strtolower($dataUser['username']).'.password', $dataUser['password'], PICONTROL_PATH.'resources/config/');
			setConfig('user:user_'.strtolower($dataUser['username']).'.last_login', 0, PICONTROL_PATH.'resources/config/');
			
			unlink(CACHE_PATH.'user.cache.php');
			
			if (rename(PICONTROL_PATH.'install', PICONTROL_PATH.'install_'.generateUniqId(32, false)) !== false)
				$tpl->redirect('../');
			else
				$tpl->msg('error', _t('Fehler'), _t('Leider konnte die Installation nicht erfolgreich abgeschlossen werden! Bitte l&ouml;sche den Ordner "%s" oder benenne ihn um. Wenn das erledigt ist, kommst du <a href="%s">hier zum Pi Control</a>.', PICONTROL_PATH.'install', '../'));
		}
		else
			$tpl->msg('error', _t('Fehler'), _t('Leider ist ein Fehler beim Auslesen des Pi Control Benutzers aufgetreten. Bitte wiederhole die Installation.'));
	}
	else
		$tpl->msg('error', _t('Fehler'), _t('Leider ist ein Fehler beim Auslesen des Pi Control Benutzers aufgetreten! Bitte wiederhole die Installation.'));
}

$tpl->assign('configUpdateNotification', $config['url']['updateNotification']);

$tpl->draw('install_finish');
?>