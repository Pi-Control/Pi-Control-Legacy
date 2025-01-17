<?php
define('PICONTROL', true);

$doNotCheckForAuthentification = true;
(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/main.function.php')					or die('Error: 0x0001');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0002');

$api = new API;

$api->addData('version', [
	'name' => $config['version']['version'],
	'code' => $config['version']['versioncode'],
	'androidCompLevel' => $config['version']['android_comp_level']
]);
$api->addData('installed', (file_exists(INSTALL_PATH) && is_dir(INSTALL_PATH)) ? false : true);
$api->addData('language', $globalLanguage);
$api->addData('theme', getConfig('main:theme.color', 'blue'));
$api->addData('label', getConfig('main:main.label', 'Raspberry Pi'));

$api->display();
?>