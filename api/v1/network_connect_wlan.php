<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../resources/init.php')	or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'main/tpl.class.php')						or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');
(include_once LIBRARY_PATH.'api/api.class.php')							or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0002');
(include_once LIBRARY_PATH.'network/network.class.php')					or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0003');
(include_once LIBRARY_PATH.'network/network.function.php')				or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0004');

$tpl = new PiTpl;
$tpl->setTpl($tpl);

$api = new API;

if (!isset($_POST['type']))
	$api->setError('error', 'No type set.');

switch (isset($_POST['type']) ? $_POST['type'] : '')
{
	case 'set':
		if (isset($_POST['interface'], $_POST['ssid'], $_POST['psk']) && ($pInterface = trim($_POST['interface'])) != '' && ($pSsid = trim($_POST['ssid'])) != '')
		{
			$networkInterface = new NetworkInterface($tpl);
			$pPsk = $_POST['psk'];
			
			list ($passphrase, $error) = $tpl->executeSSH('sudo wpa_passphrase '.escapeshellarg($pSsid).' '.escapeshellarg($pPsk).' | grep "psk=[[:alnum:]]"', true);
			$passphrase = trim(str_replace('psk=', '', $passphrase));
			
			$network = array('ssid' => '"'.$pSsid.'"', 'psk' => $passphrase);
			
			if (($status = addNetworkWPASupplicant($network)) === true)
			{
				$newInterface = array('auto' => true, 'protocol' => 'inet', 'method' => 'dhcp', 'iface' => array('wpa-conf' => '/etc/wpa_supplicant/wpa_supplicant.conf'));
				$networkInterface->deleteInterface($pInterface, false);
				
				if (($status2 = $networkInterface->addInterface($pInterface, $newInterface)) === true)
					$api->addData('success', 'true');
				else
					$api->setError('error', 'Errorcode: '.$status2);
			}
			else
				$api->setError('error', 'Errorcode: '.$status);
		}
		else
			$api->setError('error', 'No interface, ssid or psk set.');
			break;
	
	case 'down':
		if (isset($_POST['interface']) && ($pInterface = trim($_POST['interface'])) != '')
		{
			list ($status, $error) = $tpl->executeSSH('sudo ifdown '.escapeshellarg($pInterface));
			
			$api->addData('success', 'true');
			$api->addData('status', $status);
			$api->addData('error', $error);
		}
		else
			$api->setError('error', 'No interface set.');
			break;
	
	case 'up':
		if (isset($_POST['interface']) && ($pInterface = trim($_POST['interface'])) != '')
		{
			list ($status, $error) = $tpl->executeSSH('sudo ifup '.escapeshellarg($pInterface), 60);
			
			$api->addData('success', 'true');
			$api->addData('status', $status);
			$api->addData('error', $error);
		}
		else
			$api->setError('error', 'No interface set.');
			break;
	
	case 'get':
		if (isset($_POST['interface']) && ($pInterface = trim($_POST['interface'])) != '')
		{
			list ($status, $error) = $tpl->executeSSH('sudo /sbin/ifconfig '.escapeshellarg($pInterface).' | sed -n \'2s/[^:]*:\([^ ]*\).*/\1/p\'');
			
			$api->addData('success', 'true');
			$api->addData('ip', (filter_var(trim($status), FILTER_VALIDATE_IP) !== false) ? trim($status) : 'no ip');
			$api->addData('errorMsg', 'Konnte IP-Adresse nicht abrufen!');
			$api->addData('status', $status);
			$api->addData('error', $error);
		}
		else
			$api->setError('error', 'No interface set.');
			break;
		
	default:
		$api->setError('error', 'Unknown type.');
}

$api->display();
?>