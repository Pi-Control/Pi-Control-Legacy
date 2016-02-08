<?php
if (!defined('PICONTROL')) exit();

$tpl = new PiTpl;

$tpl->assign('errorHandler', implode('~', $tplErrorHandler));
$tpl->assign('servertime', date('H:i:s', time()-1));
$tpl->assign('version', $tplConfig['version']['version']);
$tpl->assign('helpLink', $tplConfig['url']['help']);

$tpl->draw('html_footer');
?>