<?php
(include_once LIBRARY_PATH.'/main/cache.function.php') or die($error_code['0x0006']);
$tpl->setHeaderTitle(_t('Cache'));

if (isset($_GET['clear']) && $_GET['clear'] != '')
{
	$clearCache = new Cache;
	$clearCache->setName($_GET['clear']);
	
	if ($clearCache->clear() === true)
		$tpl->msg('success', '', 'Der Cache wurder erfogreich geleert.');
	else
		$tpl->msg('error', '', 'Leider konnte der Cache nicht geleert werden!');
	
	if (isset($_GET['redirect']) && $_GET['redirect'] != '')
	{
		$tpl->redirect('?'.urldecode($_GET['redirect']));
	}
}

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	setConfig('cache:activation.cache', (isset($_POST['activation']) && $_POST['activation'] == 'checked') ? 'true' : 'false');
	
	$list = getCacheList();
	
	foreach ($list as $name => $info)
	{
		if (isset($_POST['text-'.$name]) && $_POST['text-'.$name] != '' && $_POST['text-'.$name] >= 1 && $_POST['text-'.$name] <= 9999)
			setConfig('cache:lifetime.'.$name, $_POST['text-'.$name]);
		else
			$tpl->msg('error', '', 'Bitte vergebe f&uuml;r die Speicherzeit eine g&uuml;ltige Zahl zwischen 1 und 9999.', true, 10);
		
		setConfig('cache:activation.'.$name, (isset($_POST['cb-'.$name]) && $_POST['cb-'.$name] == 'checked') ? 'true' : 'false');
	}
	
	if ($tpl->msgExists(10) === false)
		$tpl->msg('success', '', 'Die Einstellungen wurden erfolgreich gespeichert.', true, 10);
}

$tpl->assign('cache-activation', (getConfig('cache:activation.cache', 'false') == 'true') ? true : false);
$tpl->assign('cache-files', getCacheList(true));

$tpl->draw('settings/cache');
?>