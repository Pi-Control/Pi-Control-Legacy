<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'install/install.function.php') or die('Error: 0x0010');
$tpl->setHeaderTitle(_t('Problembehandlung'));

$filesFolders = fileFolderPermission();
$filesFoldersError = false;

foreach ($filesFolders as $file => $info)
{
	if ($info['error'] === true)
	{
		$filesFoldersError = true;
		break;
	}
}

$tpl->assign('filesFolders', $filesFolders);
$tpl->assign('filesFoldersError', $filesFoldersError);
$tpl->assign('configHelp', $config['url']['help']);

$tpl->draw('install_troubleshooting');
?>