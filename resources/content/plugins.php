<?php
$tpl->setHeaderTitle(_t('Plugins'));

$tpl->assign('plugins', pluginList());

$tpl->draw('plugins');
?>