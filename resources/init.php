<?php
if (!defined('PICONTROL')) exit();

$initConfig = parse_ini_file('config/init.config.ini.php');

if (!isset($config))
{
	$config = array(
		'ssh' => array(
			'ssh_ip'							=> '127.0.0.1'
		),
		'version' => array(
			'version'							=> '2.0.6 Beta',
			'versioncode'						=> 23,
			'android_comp_level'				=> 18
		),
		'url' => array(
			'update'							=> 'https://pi-control.de/service/v1/update/',
			'updateDownload'					=> 'https://pi-control.de/?service=update',
			'updateNotification'				=> 'https://pi-control.de/?service=update_notification',
			'plugin'							=> 'https://pi-control.de/service/v1/plugin/',
			'pluginDownload'					=> 'https://pi-control.de/?service=plugin',
			'temperatureMonitoring'				=> 'https://pi-control.de/service/v1/temperature_monitoring/',
			'help'								=> 'https://pi-control.de/?service=help'
		)
	);
}

defined('PICONTROL_PATH')	or define('PICONTROL_PATH',	realpath(dirname(__FILE__).'/../').'/');
defined('RESOURCE_PATH')	or define('RESOURCE_PATH',	realpath(dirname(__FILE__)).'/');
defined('LIBRARY_PATH')		or define('LIBRARY_PATH',	realpath(dirname(__FILE__).'/library/').'/');
defined('CONTENT_PATH')		or define('CONTENT_PATH',	realpath(dirname(__FILE__).'/content/').'/');
defined('CONFIG_PATH')		or define('CONFIG_PATH',	realpath(dirname(__FILE__).'/config/').'/');
defined('PLUGINS_PATH')		or define('PLUGINS_PATH',	realpath(dirname(__FILE__).'/plugins/').'/');
defined('UPDATE_PATH')		or define('UPDATE_PATH',	realpath(dirname(__FILE__).'/update/').'/');
defined('TEMPLATES_PATH')	or define('TEMPLATES_PATH',	realpath(dirname(__FILE__).'/../public_html/templates/').'/');
defined('TEMPLATES2_PATH')	or define('TEMPLATES2_PATH',realpath(dirname(__FILE__).'/templates/').'/');
defined('LOG_PATH')			or define('LOG_PATH',		realpath(dirname(__FILE__).'/log/').'/');
defined('CRON_PATH')		or define('CRON_PATH',		realpath(dirname(__FILE__).'/cron/').'/');
defined('LANGUAGE_PATH')	or define('LANGUAGE_PATH',	realpath(dirname(__FILE__).'/languages/').'/');
defined('CACHE_PATH')		or define('CACHE_PATH',		realpath(dirname(__FILE__).'/cache/').'/');
defined('INSTALL_PATH')		or define('INSTALL_PATH',	realpath(dirname(__FILE__).'/../').'/install/');

$globalLanguage				= isset($initConfig['language']) ? $initConfig['language'] : 'de';
$globalLanguageArray		= array();

if (isset($_GET['debug']))
{
	if ($_GET['debug'] == 'hide')
	{
		setcookie('debug', NULL, 0);
		unset($_COOKIE['debug']);
	}
	else
	{
		setcookie('debug', 'debug_mode', time()+3600);
		$_COOKIE['debug'] = 'debug_mode'; // Setze, damit direkt verfuegbar
	}
}

$errorHandler = array();
function myErrorHandler($code, $msg, $file, $line)
{
	global $errorHandler;
	$errorHandler[] = 'Fehler ['.$code.']: '.$msg.' in der Datei '.$file.', Zeile '.$line;
	
	if (isset($_COOKIE['debug']) && $_COOKIE['debug'] == 'debug_mode')
		return false;
	else
		return false; // true
}

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 1);
set_error_handler('myErrorHandler');

header('Content-Type: text/html; charset=utf-8');

if (isset($_COOKIE['debug'], $_GET['s']) && $_COOKIE['debug'] == 'debug_mode')
	echo '<!DOCTYPE HTML><div style="background: #F44336; color: #FFFFFF; padding: 3px;">DEBUG: PHP-Fehlermeldungen werden angezeigt. <a href="'.$_SERVER['REQUEST_URI'].'&debug=hide" style="color: #FFFF00;">Deaktivieren.</a></div>';

if (!isset($doNotCheckForAuthentification))
	(include LIBRARY_PATH.'main/authentification.php') or die('Nicht gefunden!');
?>