<?php
if (!defined('PICONTROL')) exit();

$tpl->assign('overviewUrl', '?s=overview');

if (isset($_GET['restart']) && $_GET['restart'] == '')
{
	$tpl->setHeaderTitle(_t('Neu starten'));
	
	list ($SSHReturn, $SSHError) = $tpl->executeSSH('sudo /sbin/shutdown -r now', true);
	
	if (!empty($SSHError))
		$tpl->msg('error', _t('Neu starten nicht m&ouml;glich'), nl2br($SSHError));
	else
	{
		$jsTranslations = array();
		$jsTranslations[] = 'Online - Du wirst sofort weitergeleitet';
		$jsTranslations[] = 'Offline';
		
		$tpl->assign('jsTranslations', $jsTranslations);
		
		$tpl->draw('restart');
	}
}
else
{
	$tpl->setHeaderTitle(_t('Herunterfahren'));
	
	list ($SSHReturn, $SSHError) = $tpl->executeSSH('sudo /sbin/shutdown -h now', true);
	
	if (!empty($SSHError))
		$tpl->msg('error', _t('Herunterfahren nicht m&ouml;glich'), nl2br($SSHError));
	else
		$tpl->draw('shutdown');
}
?>