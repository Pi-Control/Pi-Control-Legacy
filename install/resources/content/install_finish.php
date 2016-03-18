<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'cache/cache.function.php') or die($error_code['0x0007']);
$tpl->setHeaderTitle(_t('Abschließen'));

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	$dataUser = json_decode(readFromFile('user'), true);
	
	if (isset($dataUser['username'], $dataUser['password']) && $dataUser['username'] != '' && $dataUser['password'] != '')
	{
		if (getConfig('user:user_'.strtolower($dataUser['username']).'.username', '', PICONTROL_PATH.'resources/config/') != '')
		{
			setConfig('user:user_'.strtolower($dataUser['username']).'.username', $dataUser['username'], PICONTROL_PATH.'resources/config/');
			setConfig('user:user_'.strtolower($dataUser['username']).'.created', time(), PICONTROL_PATH.'resources/config/');
			setConfig('user:user_'.strtolower($dataUser['username']).'.password', $dataUser['password'], PICONTROL_PATH.'resources/config/');
			setConfig('user:user_'.strtolower($dataUser['username']).'.last_login', 0, PICONTROL_PATH.'resources/config/');
		}
	}
	
	$tpl->redirect('../');
}

$tpl->draw('install_finish');
?>