<?php
function getNetworkInterfaces($removeOther = true)
{
	$file = shell_exec('cat /etc/network/interfaces');
	$lines = explode(PHP_EOL, $file);
	$interfaces = array();
	$isInterface = NULL;
	
	foreach ($lines as $index => $line)
	{
		if (isset($line[0]) && trim($line)[0] != '#')
		{
			if (substr($line, 0, 4) == 'auto')
			{
				$isInterface = NULL;
				$interfaces[trim(substr($line, 5))]['auto'] = true;
				continue;
			}
			
			if (substr($line, 0, 10) == 'allow-auto')
			{
				$isInterface = NULL;
				$interfaces[trim(substr($line, 10))]['allow-auto'] = true;
				$interfaces[trim(substr($line, 10))]['allow-auto'][] = $index+1;
				continue;
			}
			
			if (substr($line, 0, 13) == 'allow-hotplug')
			{
				$isInterface = NULL;
				$interfaces[trim(substr($line, 13))]['allow-hotplug'] = true;
				$interfaces[trim(substr($line, 13))]['allow-hotplug'][] = $index+1;
				continue;
			}
			
			if (substr($line, 0, 5) == 'iface')
			{
				$interface = explode(' ', $line);
				$isInterface = trim($interface[1]);
				$interfaces[$isInterface]['protocol'] = $interface[2];
				$interfaces[$isInterface]['method'] = $interface[3];
				continue;
			}
			
			if ($isInterface != NULL)
			{
				preg_match('/^[\s]*([\w\d\-]*)[\s]+(.*)$/im', $line, $match);
				$interfaces[$isInterface]['iface'][$match[1]] = $match[2];
			}
		}
		elseif (isset($line[0]) && trim($line)[0] == '#')
			$interfaces['#comments#'][] = $line;
		elseif (trim($line) != '')
			$interfaces['#other#'][] = $line;
	}
	
	if ($removeOther === true)
	{
		unset($interfaces['#comments#'], $interfaces['#other#']);
		
		foreach ($interfaces as $interface => $value)
		{
			if (!isset($value['protocol']) || !isset($value['method']))
				unset($interfaces[$interface]);
		}
	}
	
	return $interfaces;
}

function writeNetworkInterface($lines)
{
	global $tpl;
	
	if (empty($lines))
		return false;
	
	$fileLines = '';
	
	if (isset($lines['#comments#']))
	{
		$comments = $lines['#comments#'];
		unset($lines['#comments#']);
	}
	
	if (isset($lines['#other#']))
	{
		$others = $lines['#other#'];
		unset($lines['#other#']);
	}
	
	foreach ($lines as $interface => $line)
	{
		if (is_array($line))
		{
			if (isset($line['auto']) && $line['auto'] == true)
				$fileLines .= 'auto '.$interface.PHP_EOL;
			
			if (isset($line['allow-auto']) && $line['allow-auto'] == true)
				$fileLines .= 'allow-auto '.$interface.PHP_EOL;
			
			if (isset($line['allow-hotplug ']) && $line['allow-hotplug '] == true)
				$fileLines .= 'allow-hotplug  '.$interface.PHP_EOL;
			
			if (isset($line['protocol'], $line['method']) && $line['protocol'] != '' && $line['method'] != '')
			{
				$fileLines .= 'iface '.$interface.' '.$line['protocol'].' '.$line['method'].PHP_EOL;
				
				if (isset($line['iface']))
				{
					foreach ($line['iface'] as $key => $value)
						$fileLines .= '    '.$key.' '.$value.PHP_EOL;
				}
			}
			
			$fileLines .= PHP_EOL;
		}
	}
	
	if (isset($others))
	{
		foreach ($others as $other)
			$fileLines .= trim($other).PHP_EOL;
	}
	
	if (isset($comments))
	{
		foreach ($comments as $comment)
			$fileLines .= trim($comment).PHP_EOL;
	}
	
	list ($status, $error) = $tpl->executeSSH('echo -e '.escapeshellarg($fileLines).' | sudo /bin/su -c "cat > /etc/network/interfaces"');
	
	if ($status == '')
		return true;
	
	return $error;
}

function addNetworkInterface($interface)
{
	if (empty($interface))
		return false;
	
	$interfaces = getNetworkInterfaces(false);
	$interfaces[key($interface)] = current($interface);
	
	return writeNetworkInterface($interfaces);
}

function deleteNetworkInterface($interface)
{
	if (empty($interface))
		return false;
	
	$interfaces = getNetworkInterfaces(false);
	unset($interfaces[$interface]);
	
	return writeNetworkInterface($interfaces);
}

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
			
			$fileLines .= '}'.PHP_EOL;
		}
		else
			$fileLines .= $line.PHP_EOL;
	}
	
	list ($status, $error) = $tpl->executeSSH('echo -e '.escapeshellarg($fileLines).' | sudo /bin/su -c "cat > /etc/wpa_supplicant/wpa_supplicant.conf"');
	
	if ($status == '')
		return true;
	
	return $error;
}

function getAllNetworkWPASupplicant()
{
	/*global $tpl;
	
	list ($status, $error) = $tpl->executeSSH('sudo cat /etc/wpa_supplicant/wpa_supplicant.conf');
	
	$splits = preg_split('/^network=/smi', $status);
	$wpas = array();
	$i = 0;
	
	foreach ($splits as $split)
	{
		if ($split[0] != '{')
			continue;
		
		$settings = explode(PHP_EOL, $split);
		foreach ($settings as $setting)
		{
			preg_match_all('/^(.*)=(.*)$/', trim($setting), $matches);
			
			if (count($matches) == 3 && isset($matches[1][0]) && $matches[1][0] != '')
				$wpas[$i][$matches[1][0]] = trim($matches[2][0], '"');
		}
		
		$i += 1;
	}
	
	return $wpas;*/
	
	global $tpl;
	
	list ($status, $error) = $tpl->executeSSH('sudo cat /etc/wpa_supplicant/wpa_supplicant.conf');
	
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
	
	list ($status, $error) = $tpl->executeSSH('echo -e '.escapeshellarg($new).' | sudo /bin/su -c "cat > /etc/hosts"');
	
	if ($status == '')
		list ($status2, $error2) = $tpl->executeSSH('echo -e '.escapeshellarg($hostname).' | sudo /bin/su -c "cat > /etc/hostname"');
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