<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')			or die('Error: 0x0001');

$tpl = new PiTpl;
$tpl->setTpl($tpl);
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(false);
$tpl->setDrawFooter(false, $config, $errorHandler);

unlink(PICONTROL_PATH.'public_html/js/settings.trouble-shooting.cron_selection.js');
unlink(PICONTROL_PATH.'public_html/templates/settings/trouble-shooting.tpl.php');
unlink(PICONTROL_PATH.'resources/content/settings/trouble-shooting.php');
unlink(PICONTROL_PATH.'resources/library/trouble-shooting/trouble-shooting.function.php');
rmdir(PICONTROL_PATH.'resources/library/trouble-shooting/');

unlink('setup.php');

$tpl->redirect('../../?s=settings&do=update&complete');
?>