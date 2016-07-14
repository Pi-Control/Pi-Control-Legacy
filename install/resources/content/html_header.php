<?php
if (!defined('PICONTROL')) exit();

$tpl = new PiTpl;
$tpl->assign('title', (isset($data['title']) && $data['title'] != '') ? $data['title'] : 'Pi Control');

if (isset($_GET['s']) && $_GET['s'] == 'install' || !isset($_GET['s']))
{
	if (function_exists('curl_init') === true)
		$picontrolUpdate = checkUpdate();
}

$referer = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

if ($referer != '')
	$referer = '&referer='.urlencode($referer);

// Uebersetzung
$jsTranslations = $data['jsTranslations'];
$jsTranslations[] = 'Leider ist ein unerwarteter Fehler aufgetreten. Bitte schließe das Feedback-Fenster und versuche es erneut. Andernfalls, schreibe mir unter <a href="%%s" target="_blank">Kontakt</a>.';
$jsTranslations[] = 'Schließen';
$jsTranslations[] = 'F&uuml;r das Feedback m&uuml;ssen noch einige Daten gesammelt werden.';
$jsTranslations[] = 'Diagnosedaten wurden gesammelt. Beim Klick auf den folgenden Button wird ein neues Fenster ge&ouml;ffnet.';
$jsTranslations[] = 'Feedback &ouml;ffnen';
$tpl->assign('jsTranslations', getTranslatedArrayForJs($jsTranslations));

$tpl->assign('updatePicontrol', isset($picontrolUpdate) ? $picontrolUpdate : '');
$tpl->assign('referer', $referer);
$tpl->assign('langUrl', (isset($_GET['lang']) && $_GET['lang'] != '') ? '&amp;lang='.$_GET['lang'] : '');

$tpl->draw('html_header');
?>