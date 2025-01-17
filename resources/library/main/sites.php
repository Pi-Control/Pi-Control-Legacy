<?php
if (!defined('PICONTROL')) exit();

$site = array(
	'overview' => 'overview.php',
	'detailed_overview' => 'detailed_overview.php',
	'installed_packages' => 'installed_packages.php',
	'network' => 'network.php',
	'network_connect' => 'network_connect.php',
	'network_configuration' => 'network_configuration.php',
	'statistic' => 'statistic.php',
	'plugins' => 'plugins.php',
	'discover_plugins' => 'discover_plugins.php',
	'settings' => 'settings.php',
	'ssh_login' => 'ssh_login.php',
	'terminal' => 'terminal.php',
	'shutdown' => 'shutdown.php',
	'users_groups' => 'users_groups.php',
	'logs' => 'logs.php',
	'processes' => 'processes.php'
);

$include = array(
	'login' => 'resources/content/login.php',
	'update' => 'resources/update/update_picontrol.php',
	'download_plugin' => 'resources/plugins/download_plugin.php',
	'update_plugin' => 'resources/plugins/update_plugin.php',
	'feedback' => LIBRARY_PATH.'feedback/feedback.php'
);
?>