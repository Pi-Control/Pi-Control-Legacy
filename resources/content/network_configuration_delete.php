<?php
$interfaceName = urldecode($_GET['delete']);

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (isset($_POST['checksum']) && ($pChecksum = trim($_POST['checksum'])) != '')
	{
		if ($pChecksum == $networkInterface->getInterfaceHash())
		{
			if ($networkInterface->deleteInterface($interfaceName) === true)
				$tpl->redirect('?s=network_configuration&msg=delete');
			else
				$tpl->msg('error', '', 'Leider konnte das Interface nicht gel&ouml;scht werden. Es ist w&auml;hrend der &Uuml;bertragung ein Fehler aufgetreten.');
		}
		else
			$tpl->msg('error', '', 'Leider wurde die Konfigurationsdatei zwischenzeitlich ver&auml;ndert, versuche es deshalb noch einmal.');
	}
}

$tpl->assign('checksum', $networkInterface->getInterfaceHash());
$tpl->assign('interfaceName', $interfaceName);

$tpl->draw('network_configuration_delete');
?>