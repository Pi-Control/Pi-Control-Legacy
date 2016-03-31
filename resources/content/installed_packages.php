<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'main/rpi.function.php') or die('Error: 0x0010');
$tpl->setHeaderTitle(_t('Installierte Pakete'));

$packages = rpi_getInstalledPackages();

$tpl->assign('installedPackages', $packages);
$tpl->assign('installedPackagesCount', count($packages));

$tpl->draw('installed_packages');
?>