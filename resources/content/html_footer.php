<?php
$tpl = new PiTpl;

$tpl->assign('errorHandler', implode('~', $tplErrorHandler));
$tpl->assign('servertime', date('H:i:s', time()-1));
$tpl->assign('version', $tplConfig['versions']['version']);
$tpl->assign('help_link', $tplConfig['urls']['helpUrl']);

$tpl->draw('html_footer');
?>