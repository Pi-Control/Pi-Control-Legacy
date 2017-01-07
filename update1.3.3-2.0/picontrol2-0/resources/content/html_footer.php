<?php
if (!defined('PICONTROL')) exit();

$tpl = new PiTpl;

global $globalLanguage;

$tpl->assign('errorHandler', urlencode(base64_encode(json_encode($tplErrorHandler))));
$tpl->assign('version', $tplConfig['version']['version']);
$tpl->assign('helpLink', $tplConfig['url']['help'].getURLLangParam(false, true, true));
$tpl->assign('language', $globalLanguage);

$tpl->draw('html_footer');
?>