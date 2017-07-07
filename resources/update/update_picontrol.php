<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../init.php')	or die('Error: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')			or die('Error: 0x0001');
(include_once LIBRARY_PATH.'main/main.function.php')		or die('Error: 0x0002');
(include_once LIBRARY_PATH.'update/update.class.php')		or die('Error: 0x0003');

$tpl = new PiTpl;
$tpl->setTpl($tpl);
$tpl->setTplFolder(TEMPLATES_PATH);
$tpl->setDrawHeader(false);
$tpl->setDrawFooter(false, $config, $errorHandler);

$updateController = new UpdateController();
$updateController->setStage(getConfig('main:update.stage', 'release'));
$updateStatus = $updateController->fetchData();

if ($updateStatus === true) {
	$nextUpdate = $updateController->getNextUpdate();
	
	if ($nextUpdate instanceof Update) {
		$downloadStatus = $updateController->download($nextUpdate);
		
		if ($downloadStatus === true) {
			$tpl->redirect('?s=settings&do=update&complete');
		} elseif ($downloadStatus === 10) {
			$tpl->redirect('resources/update/setup.php');
		} elseif ($downloadStatus === 1) {
			$errorMsg = _t('Die Aktualisierung wurde nicht vollst&auml;ndig heruntergeladen. Bitte versuche es erneut. Sollte der Fehler weiterhin auftreten, schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiterhelfen kann.', 'https://willy-tech.de/kontakt/');
		} else {
			$errorMsg = _t('Leider ist w&auml;hrend der Aktualisierung ein Fehler aufgetreten: %s<br />Bitte schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.', $downloadStatus, 'https://willy-tech.de/kontakt/');
		}
	} else {
		$errorMsg = _t('Aktuell steht keine Aktualisierung zur Verf&uuml;gung!');
	}
} else {
	$errorMsg = _t('Leider ist w&auml;hrend der Aktualisierung ein Fehler aufgetreten: %s<br />Bitte schreibe mir unter <a href="%s" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.', $updateStatus, 'https://willy-tech.de/kontakt/');
}

if (isset($errorMsg)) {
	$tpl->assign('content', '<div class="inner-header"><span>'._t('Aktualisierung').'</span></div><div class="inner red">'.$errorMsg.'</div><div class="inner-end"><a href="?s=settings&amp;do=update" class="button">'._t('Zur&uuml;ck zur Aktualisierung').'</a></div>');
	$tpl->draw('single_box');
}