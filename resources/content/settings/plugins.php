<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Einstellungen zu Plugins'));

$tpl->assign('plugins', pluginList());

$tpl->draw('settings/plugins');
?>