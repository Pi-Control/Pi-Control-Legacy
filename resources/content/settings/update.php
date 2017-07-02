<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'troubleshooting/troubleshooting.function.php')	or die('Error: 0x0010');
(include_once LIBRARY_PATH.'update/update.class.php')						or die('Error: 0x0011');
$tpl->setHeaderTitle(_t('Aktualisierung'));

$updateController = new UpdateController();
$updateController->setStage(getConfig('main:update.stage', 'release'));
$updateStatus = $updateController->fetchData();

if ($updateStatus === true)
	$updateStatus = $updateController->getNextUpdate();

if (isset($_POST['update']) && $_POST['update'] != '')
	$tpl->redirect('?i=update');

if (isset($_GET['complete']))
{
	checkUpdate();
	$tpl->msg('success', _t('Pi Control auf Version %s aktualisiert', $config['version']['version']), _t('Dein Pi Control wurde erfolgreich aktualisiert und ist nun einsatzbereit. Sollten Probleme auftreten, klicke einfach unten auf "Feedback" und schreibe mir. Viel Spaß!<br /><br />Tipp: Leere deinen Browser-Cache mit Strg + F5 (Windows) / &#8997;&#8984; + E (OS X / Safari) / &#8679;&#8984; + R (OS X / Chrome)'));
}

if (isset($_POST['beta']) && $_POST['beta'] != '')
{
	$newStage = getConfig('main:update.stage', 'release') == 'release' ? 'beta' : 'release';
	
	if ($newStage == 'release')
		$tpl->msg('success', _t('Erfolgreich zur&uuml;ckgetreten'), _t('Du bist erfolgreich von Pi Control Beta zur&uuml;ckgetreten. Ab sofort erh&auml;ltst du ausschließlich stabile Aktualisierungen.'));
	else
		$tpl->msg('success', _t('Erfolgreich teilgenommen'), _t('Vielen Dank f&uuml;r deine Teilnahme an Pi Control Beta. Ab sofort erh&auml;ltst du Beta-Aktualisierungen.'));
	
	setConfig('main:update.stage', $newStage);
	$updateController->setStage($newStage);
	$updateStatus = $updateController->getNextUpdate();
}

$filesFolders = fileFolderPermission();

$fileError = (array_search(true, array_column($filesFolders, 'error')))	? true : false;

if ($fileError === true)
	$tpl->msg('error', _t('Aktualisierung blockiert'), _t('Es wurde mindestens ein Fehler mit den Dateien oder Ordnern des Pi Control festgestellt! Bitte behebe zun&auml;chst das Problem mit Hilfe der <a href="%s">Problembehandlung</a>, ansonsten ist eine Aktualisierung nicht m&ouml;glich.', '?s=settings&amp;do=troubleshooting'), false);

$tpl->assign('updateError', $fileError);
$tpl->assign('updateStatus', $updateStatus);
$tpl->assign('updateStage', getConfig('main:update.stage', 'release'));
$tpl->assign('configVersion', $config['version']['version']);
$tpl->assign('configMailUrl', $config['url']['updateNotification'].getURLLangParam());

$tpl->draw('settings/update');
?>