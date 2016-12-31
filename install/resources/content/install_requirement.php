<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'main/rpi.function.php')			or die('Error: 0x0010');
(include_once LIBRARY_PATH.'install/install.function.php')	or die('Error: 0x0011');
$tpl->setHeaderTitle(_t('Anforderungen'));

// PHP
$phpVersion = array('version' => PHP_VERSION, 'status' => false);
$phpSSH = array('status' => false);
$phpMcrypt = array('status' => false);
$phpCLI = array('status' => false);
$phpCURL = array('status' => false);
$phpZipArchive = array('status' => false);
$phpAllowUrlFopen = array('status' => false);
$filesFoldersExist = array('count' => 0, 'status' => true);
$filesFoldersPermission = array('count' => 0, 'status' => true);
$otherDistribution = array('version' => rpi_getDistribution(), 'status' => false);
$otherCookie = array('status' => false);
$error = false;

if (version_compare(PHP_VERSION, '5.5.0') >= 0)
	$phpVersion['status'] = true;

if (extension_loaded('ssh2'))
	$phpSSH['status'] = true;

if (function_exists('mcrypt_encrypt') !== false)
	$phpMcrypt['status'] = true;

if (trim(exec('dpkg -s php5-cli | grep Status: ')) != '' || trim(exec('dpkg -s php7.0-cli | grep Status: ')) != '')
	$phpCLI['status'] = true;

if (function_exists('curl_init') !== false)
	$phpCURL['status'] = true;

if (class_exists('ZipArchive') !== false)
	$phpZipArchive['status'] = true;

if (ini_get('allow_url_fopen') !== false)
	$phpAllowUrlFopen['status'] = true;

// Dateien und Ordner
$filesFolders = fileFolderPermission();

foreach ($filesFolders as $file => $info)
{
	if ($info['error'] === true)
	{
		if ($info['existsBool'] === false || $info['filesizeBool'] === false)
		{
			$filesFoldersExist['count'] += 1;
			$filesFoldersExist['status'] = false;
		}
		
		if ($info['permissionBool'] === false || $info['userGroupBool'] === false)
		{
			$filesFoldersPermission['count'] += 1;
			$filesFoldersPermission['status'] = false;
		}
	}
}

// Sonstiges
if ($otherDistribution['version'] == 'Raspbian GNU/Linux 7' || $otherDistribution['version'] == 'Raspbian GNU/Linux 8')
	$otherDistribution['status'] = true;

if (isset($_COOKIE['_pi-control_install_language']) && $_COOKIE['_pi-control_install_language'] != '')
	$otherCookie['status'] = true;
else
	setCookie('_pi-control_install_language', $globalLanguage);

// Button
if ($phpVersion['status'] === false || $phpSSH['status'] === false || $phpMcrypt['status'] === false || $phpCLI['status'] === false || $phpCURL['status'] === false || $phpZipArchive['status'] === false || $phpAllowUrlFopen['status'] === false || $filesFoldersExist['status'] === false || $filesFoldersPermission['status'] === false || $otherDistribution['status'] === false || $otherCookie['status'] === false)
	$error = true;

$tpl->assign('phpVersion', $phpVersion);
$tpl->assign('phpSSH', $phpSSH);
$tpl->assign('phpMcrypt', $phpMcrypt);
$tpl->assign('phpCLI', $phpCLI);
$tpl->assign('phpCURL', $phpCURL);
$tpl->assign('phpZipArchive', $phpZipArchive);
$tpl->assign('phpAllowUrlFopen', $phpAllowUrlFopen);
$tpl->assign('filesFoldersExist', $filesFoldersExist);
$tpl->assign('filesFoldersPermission', $filesFoldersPermission);
$tpl->assign('otherDistribution', $otherDistribution);
$tpl->assign('otherCookie', $otherCookie);
$tpl->assign('error', $error);
$tpl->assign('langUrl', (isset($_GET['lang']) && $_GET['lang'] != '') ? '&amp;lang='.$_GET['lang'] : '');
$tpl->assign('configHelp', $config['url']['help']);

$tpl->draw('install_requirement');
?>