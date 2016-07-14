<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'plugin/plugin.function.php')				or die('Error: 0x0001');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Error: 0x0002');

$api = new API;

$plugins = pluginList();

if (isset($_GET['id']) && !isset($_POST['id']))
	$_POST['id'] = $_GET['id'];

if (isset($_GET['action']) && !isset($_POST['action']))
	$_POST['action'] = $_GET['action'];

if (isset($_POST['id'], $plugins[$_POST['id']]))
{
	$id = $_POST['id'];
	
	if (isset($_POST['action']) && ($action = trim(urldecode($_POST['action']))) != '')
	{
		if (preg_match('/^v[0-9]\/[a-z][a-z0-9\-_]+$/i', $action) === 1)
		{
			if (file_exists(PLUGINS_PATH.$id.'/api/'.$action.'.php') && is_file(PLUGINS_PATH.$id.'/api/'.$action.'.php'))
			{
				initPluginConstants($id);
				include PLUGINS_PATH.$id.'/api/'.$action.'.php';
			}
			else
				$api->setError('error', 'Action not available.');
		}
		else
			$api->setError('error', 'Wrong syntax for action.');
	}
	else
		$api->addData('plugin', $plugins[$id]);
}
else
	$api->addData('plugins', $plugins);

$api->display();
?>