<?php
if (!defined('PICONTROL')) exit();

$tpl->assign('overviewUrl', '?s=overview');

if (isset($_GET['restart']) && $_GET['restart'] == '')
{
	$tpl->setHeaderTitle(_t('Neustarten'));
	
	$SSHReturn = $tpl->executeSSH('sudo /sbin/shutdown -r now', true);
	
	if (!empty($SSHReturn))
		$tpl->msg('error', $tpl->_t('Neustarten nicht m&ouml;glich'), nl2br($SSHReturn));
	else
		$tpl->draw('restart');
}
else
{
	$tpl->setHeaderTitle(_t('Herunterfahren'));
	
	$SSHReturn = $tpl->executeSSH('sudo /sbin/shutdown -h now', true);
	
	if (!empty($SSHReturn))
		$tpl->msg('error', $tpl->_t('Herunterfahren nicht m&ouml;glich'), nl2br($SSHReturn));
	else
		$tpl->draw('shutdown');
}
?>