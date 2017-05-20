<?php
if (!defined('PICONTROL')) exit();

function writeNetworkWPASupplicant($lines)
{
	global $tpl;
	
	if (empty($lines))
		return false;
	
	$fileLines = '';
	
	foreach ($lines as $line)
	{
		if (is_array($line))
		{
			$fileLines .= 'network={'.PHP_EOL;
			
			foreach ($line as $key => $value)
				$fileLines .= '    '.$key.'='.$value.PHP_EOL;
			
			$fileLines .= '}'.PHP_EOL.PHP_EOL;
		}
		else
			$fileLines .= $line.PHP_EOL;
	}
	
	list ($status, $error, $exitStatus) = $tpl->executeSSH('echo -e '.escapeshellarg($fileLines).' | sudo /bin/su -c "cat > /etc/wpa_supplicant/wpa_supplicant.conf"');
	
	if ($status == '')
		return true;
	
	return $error;
}

function getAllNetworkWPASupplicant()
{
	global $tpl;
	
	list ($status, $error, $exitStatus) = $tpl->executeSSH('sudo cat /etc/wpa_supplicant/wpa_supplicant.conf');
	
	$splits = explode(PHP_EOL, $status);
	$lines = array();
	$isNetwork = false;
	$network = array();
	
	foreach ($splits as $index => $split)
	{
		if (count($split) == 0 || (isset($split[0]) && trim($split)[0] == '#'))
		{
			$lines[] = $split;
			continue;
		}
		
		if (substr($split, 0, 8) == 'network=' && $isNetwork == false)
		{
			$network = array();
			$isNetwork = true;
			continue;
		}
		
		if (isset($split[0]) && $split[0] == '}' && $isNetwork == true)
		{
			$lines[] = $network;
			$isNetwork = false;
			continue;
		}
		
		if ($isNetwork == true)
		{
			preg_match_all('/^(.*)=(.*)$/', trim($split), $matches);
			
			if (count($matches) == 3 && isset($matches[1][0]) && $matches[1][0] != '')
				$network[$matches[1][0]] = $matches[2][0];
			
			continue;
		}
		
		if (trim($split) != '')
			$lines[] = $split;
	}
	
	return $lines;
}

function addNetworkWPASupplicant($network)
{
	global $tpl;
	
	$lines = getAllNetworkWPASupplicant();
	$isAdded = false;
	
	foreach ($lines as $index => $line)
	{
		if (!is_array($line))
			continue;
		
		if (!isset($line['id_str']) && $line['ssid'] == $network['ssid'])
		{
			$lines[$index] = $network;
			$isAdded = true;
			break;
		}
	}
	
	if ($isAdded == false)
		$lines[] = $network;
	
	return writeNetworkWPASupplicant($lines);
}

function editHostname($hostname)
{
	global $tpl;
	
	$hosts = shell_exec('cat /etc/hosts');
	
	if (empty($hosts))
		return 5;
	
	$new = preg_replace('/^(127\.0\.1\.1[\s]+)(.+)$/im', '$1'.$hostname, $hosts);
	
	list ($status, $error, $exitStatus) = $tpl->executeSSH('echo -e '.escapeshellarg($new).' | sudo /bin/su -c "cat > /etc/hosts"');
	
	if ($status == '')
		list ($status2, $error2, $exitStatus2) = $tpl->executeSSH('echo -e '.escapeshellarg($hostname).' | sudo /bin/su -c "cat > /etc/hostname"');
	else
		return $status;
	
	if ($status2 == '')
		return true;
	
	return $status2;
}

function formatInterfaceProtocol($string)
{
	switch ($string)
	{
		case 'inet':
		return 'IPv4';
			break;
		case 'inet6':
		return 'IPv6';
			break;
		case 'ipx':
		return 'IPX/SPX';
			break;
	}
}

function formatInterfaceMethod($string)
{
	switch ($string)
	{
		case 'loopback':
		return 'Loopback';
			break;
		case 'dhcp':
		return 'Dynamisch';
			break;
		case 'static':
		return 'Statisch';
			break;
		case 'manual':
		return 'Manuell';
			break;
	}
}
?>