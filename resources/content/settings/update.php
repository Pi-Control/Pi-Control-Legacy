<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'trouble-shooting/trouble-shooting.function.php') or die('Error: 0x0010');
$tpl->setHeaderTitle(_t('Aktualisierung'));

if (isset($_POST['update']) && $_POST['update'] != '')
	$tpl->redirect('?i=update');

if (isset($_GET['complete']))
{
	checkUpdate();
	$tpl->msg('success', _t('Pi Control auf Version %s aktualisiert', $config['version']['version']), _t('Dein Pi Control wurde erfolgreich aktualisiert und ist nun einsatzbereit. Sollten Probleme auftreten, klicke einfach unten auf "Feedback" und schreibe mir. Viel Spaß!<br /><br />Tipp: Leere deinen Browser-Cache mit Ctrl + F5'));
}

$filesFolders = fileFolderPermission();

$fileError = (array_search(true, array_column($filesFolders, 'error')))	? true : false;

if ($fileError === true)
	$tpl->msg('error', _t('Aktualisierung blockiert'), _t('Es wurde mindestens ein Fehler mit den Dateien oder Ordnern des Pi Control festgestellt! Bitte behebe zun&auml;chst das Problem mit Hilfe der <a href="%s">Problembehandlung</a>, ansonsten ist eine Aktualisierung nicht m&ouml;glich.', '?s=settings&amp;do=trouble-shooting'), false);

$tpl->assign('updateError', $fileError);
$tpl->assign('updateStatus', checkUpdate());
$tpl->assign('configVersion', $config['version']['version']);
$tpl->assign('configMailUrl', $config['url']['updateNotification']);

$tpl->draw('settings/update');
?>