<?php
$config = array(
	'ssh' => array(
		'ssh_ip'				=> $_SERVER['SERVER_ADDR']
	),
	'versions' => array(
		'version'				=> '2.0',
		'versioncode'			=> 16,
		'android_comp_level'	=> 4
	),
	'urls' => array(
		'baseUrl'				=> 'http://picontrol.willy-tech.de/web/1-0/',
		'updateUrl'				=> 'http://picontrol.willy-tech.de/web/1-0/updates.xml',
		'updateDownloadUrl'		=> 'http://picontrol.willy-tech.de/web/1-0/?s=update&file=',
		'pluginUrl'				=> 'http://picontrol.willy-tech.de/web/1-0/plugins.xml',
		'pluginDownloadUrl'		=> 'http://picontrol.willy-tech.de/web/1-0/plugins/',
		'tempMonitoringUrl'		=> 'http://picontrol.willy-tech.de/web/1-0/?s=temp_mail_',
		'helpUrl'				=> 'http://picontrol.willy-tech.de/web/1-0/?s=help'
	),
	'paths' => array(
		'main'					=> realpath(dirname(__FILE__).'/../'),
		'resources'				=> realpath(dirname(__FILE__)),
		'images'				=> realpath(dirname(__FILE__).'/../public_html/img/'),
		'install'				=> realpath(dirname(__FILE__).'/../install/')
	)
);

defined('RESOURCE_PATH')	or define('RESOURCE_PATH',	realpath($config['paths']['resources']).'/');
defined('LIBRARY_PATH')		or define('LIBRARY_PATH',	realpath($config['paths']['resources'].'/library/').'/');
defined('CONTENT_PATH')		or define('CONTENT_PATH',	realpath($config['paths']['resources'].'/content/').'/');
defined('CONFIG_PATH')		or define('CONFIG_PATH',	realpath($config['paths']['resources'].'/config/').'/');
defined('PLUGINS_PATH')		or define('PLUGINS_PATH',	realpath($config['paths']['resources'].'/plugins/').'/');
defined('UPDATE_PATH')		or define('UPDATE_PATH',	realpath($config['paths']['resources'].'/update/').'/');
defined('TEMPLATES_PATH')	or define('TEMPLATES_PATH',	realpath(dirname(__FILE__).'/../public_html/templates/').'/');
defined('TEMP_PATH')		or define('TEMP_PATH',		realpath($config['paths']['resources'].'/temp/').'/');
defined('LOG_PATH')			or define('LOG_PATH',		realpath($config['paths']['resources'].'/log/').'/');
defined('CRON_PATH')		or define('CRON_PATH',		realpath($config['paths']['resources'].'/cron/').'/');
defined('LANGUAGE_PATH')	or define('LANGUAGE_PATH',	realpath($config['paths']['resources'].'/languages/').'/');

$globalLanguage				= 'en';


if (isset($_GET['debug']))
{
	if ($_GET['debug'] == 'hide')
	{
		setcookie('debug', NULL, -1);
		unset($_COOKIE['debug']);
	}
	else
		setcookie('debug', 'debug_mode', time()+3600);
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
	echo '<!DOCTYPE HTML><div style="background: #FF0000; color: #FFFFFF; padding: 2px;">DEBUG: PHP-Fehlermeldungen werden angezeigt. <a href="'.$_SERVER['REQUEST_URI'].'&debug=hide" style="color: #FFFF00;">Deaktivieren.</a></div>';
?>