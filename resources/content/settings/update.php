<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'trouble-shooting/trouble-shooting.function.php') or die($error_code['0x0006']);
$tpl->setHeaderTitle(_t('Aktualisierung'));

if (isset($_POST['update']) && $_POST['update'] != '')
	$tpl->redirect('?i=update');

if (isset($_GET['complete']))
{
	checkUpdate();
	$tpl->msg('success', 'Pi Control auf Version '.$config['version']['version'].' aktualisiert', 'Dein Pi Control wurde erfolgreich aktualisiert und ist nun einsatzbereit. Sollten Probleme auftreten, klicke einfach unten auf "Feedback" und schreibe mir. Viel Spaß!<br /><br />Tipp: Leere deinen Browser-Cache mit Ctrl + F5');
}

$filesFolders = fileFolderPermission();

$fileError = (array_search(true, array_column($filesFolders, 'error')))	? true : false;

if ($fileError === true)
	$tpl->msg('error', 'Aktualisierung blockiert', 'Es wurde mindestens ein Fehler mit den Dateien oder Ordnern des Pi Control festgestellt! Bitte behebe zun&auml;chst das Problem mit Hilfe der <a href="?s=settings&amp;do=trouble-shooting">Problembehandlung</a>, ansonsten ist eine Aktualisierung nicht m&ouml;glich.', false);

$tpl->assign('updateError', $fileError);
$tpl->assign('updateStatus', checkUpdate());
$tpl->assign('configVersion', $config['version']['version']);
$tpl->assign('configMailUrl', $config['url']['updateNotification']);

$tpl->draw('settings/update');
?>