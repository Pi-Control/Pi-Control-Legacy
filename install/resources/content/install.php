<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Vorwort'));

$tpl->msg('error', _t('Aktuell bekannte Einschr&auml;nkungen'), _t('- Das Terminal funktioniert nur unter HTTP, nicht HTTPS.<br />- Noch keine Android App. Wird ebenfalls nachgereicht.'));

$tpl->assign('langUrl', (isset($_GET['lang']) && $_GET['lang'] != '') ? '&amp;lang='.$_GET['lang'] : '');

$tpl->draw('install');
?>