<?php
if (!defined('PICONTROL')) exit();

$tpl = new PiTpl;

$tpl->assign('errorHandler', urlencode(base64_encode(json_encode($tplErrorHandler))));
$tpl->assign('version', $tplConfig['version']['version']);
$tpl->assign('helpLink', $tplConfig['url']['help']);

$tpl->draw('html_footer');
?>