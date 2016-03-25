<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Sprachauswahl'));

$dataLanguage = json_decode(readFromFile('language'), true);

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (isset($_POST['language']) && in_array($_POST['language'], array('de', 'en')))
	{
		$return = writeToFile('language', json_encode(array('language' => $_POST['language'])));
		setCookie('_pi-control_install_language', $_POST['language']);
		$tpl->redirect('?s=install_requirement&lang='.$_POST['language']);
	}
	
	$tpl->msg('error', '', 'Konnte die gew&auml;hlte Sprache nicht finden! Bitte versuche es noch einmal.');
}

$languages = array('de', 'en');
$languageArray = array(
						_t2('1. Schritt', $languages),
						_t2('Fortschritt', $languages),
						_t2('Bitte w&auml;hle nachfolgend aus den vorhandenen Sprachen, deine bevorzugte Sprache aus. Die Sprache kann nachtr&auml;glich ge&auml;ndert werden.', $languages),
						_t2('Sprachauswahl', $languages),
						_t2('N&auml;chster Schritt', $languages),
						_t2('Konnte die gew&auml;hlte Sprache nicht finden! Bitte versuche es noch einmal.', $languages),
						_t2('&Uuml;BER MICH', $languages),
						_t2('VERSION', $languages),
						_t2('LIZENZ', $languages),
						_t2('Feedback', $languages),
						_t2('Hilfe', $languages),
						_t2('App im Play Store', $languages),
						_t2('Mein Blog', $languages),
						_t2('Spenden', $languages),
						_t2('Raspberry Pi ist ein Markenzeichen<br />der %s.', $languages),
						_t2('Mit %s entwickelt von %s.', $languages)
					);

$tpl->assign('languageArray', json_encode($languageArray));
$tpl->assign('language', (isset($$dataLanguage['language'])) ? $dataLanguage['language'] : $globalLanguage);

$tpl->draw('install_language');
?>