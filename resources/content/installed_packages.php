<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'main/rpi.function.php') or die($error_code['0x0003']);
$tpl->setHeaderTitle(_t('Installierte Packete'));

$packages = rpi_getInstalledPackages();

$tpl->assign('installedPackages', $packages);
$tpl->assign('installedPackagesCount', count($packages));

$tpl->draw('installed_packages');
?>