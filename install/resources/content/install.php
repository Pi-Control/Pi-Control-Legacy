<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Vorwort'));

$tpl->assign('langUrl', (isset($_GET['lang']) && $_GET['lang'] != '') ? '&amp;lang='.$_GET['lang'] : '');

$tpl->draw('install');
?>