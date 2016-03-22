<?php
class NetworkInterface
{
	private $tpl;
	private $file;
	private $interfaces = array();
	private $comments = array();
	private $others = array();
	
	public function __construct($tpl)
	{
		$this->tpl = $tpl;
		$this->readInterfaces();
	}
	
	public function getInterfaces($removeEmpty = true)
	{
		$return = $this->interfaces;
		
		if ($removeEmpty === true)
		{
			foreach ($return as $interface => $value)
			{
				if (!isset($value['protocol']) || !isset($value['method']))
					unset($return[$interface]);
			}
		}
		
		return $return;
	}
	
	public function getInterface($interface, $removeEmpty = true)
	{
		if (!isset($this->interfaces[$interface]))
			return false;
		
		$return = $this->interfaces[$interface];
		
		if ($removeEmpty === true)
		{
			if (!isset($return['protocol']) || !isset($return['method']))
				return false;
		}
		
		return $return;
	}
	
	public function addInterface($interface, $interfaceSettings)
	{
		if (empty($interface) || empty($interfaceSettings))
			return 0;
		
		if (isset($this->interfaces[$interface], $this->interfaces[$interface]['protocol'], $this->interfaces[$interface]['method']))
			return 1;
		
		$this->interfaces[$interface] = $interfaceSettings;
		
		return $this->writeNetworkInterface();
	}
	
	public function editInterface($interface, $interfaceSettings, $newInterfaceName = NULL)
	{
		if (empty($interface) || empty($interfaceSettings))
			return 0;
		
		$this->interfaces[$interface] = array_merge($this->interfaces[$interface], $interfaceSettings);
		$this->interfaces[$interface] = array_filter($this->interfaces[$interface]);
		
		if ($newInterfaceName != NULL)
		{
			$this->interfaces[$newInterfaceName] = $this->interfaces[$interface];
			unset($this->interfaces[$interface]);
		}
		
		return $this->writeNetworkInterface();
	}
	
	public function deleteInterface($interface)
	{
		if (empty($interface))
			return false;
		
		unset($this->interfaces[$interface]);
		
		return $this->writeNetworkInterface();
	}
	
	public function existsInterface($interface, $removeEmpty = true)
	{
		if (!isset($this->interfaces[$interface]))
			return false;
		
		if ($removeEmpty === true)
		{
			if (!isset($this->interfaces[$interface]['protocol']) || !isset($this->interfaces[$interface]['method']))
				return false;
		}
		
		return true;
	}
	
	public function getInterfaceHash()
	{
		return md5(serialize($this->file));
	}
	
	private function readInterfaces()
	{
		$this->file = shell_exec('cat /etc/network/interfaces');
		$lines = explode(PHP_EOL, $this->file);
		$isInterface = NULL;
		
		foreach ($lines as $index => $line)
		{
			if (isset($line[0]) && trim($line)[0] != '#')
			{
				if (substr($line, 0, 4) == 'auto')
				{
					$isInterface = NULL;
					$this->interfaces[trim(substr($line, 5))]['auto'] = true;
					continue;
				}
				
				if (substr($line, 0, 10) == 'allow-auto')
				{
					$isInterface = NULL;
					$this->interfaces[trim(substr($line, 10))]['allow-auto'] = true;
					$this->interfaces[trim(substr($line, 10))]['allow-auto'][] = $index+1;
					continue;
				}
				
				if (substr($line, 0, 13) == 'allow-hotplug')
				{
					$isInterface = NULL;
					$this->interfaces[trim(substr($line, 13))]['allow-hotplug'] = true;
					$this->interfaces[trim(substr($line, 13))]['allow-hotplug'][] = $index+1;
					continue;
				}
				
				if (substr($line, 0, 5) == 'iface')
				{
					$interface = explode(' ', $line);
					$isInterface = trim($interface[1]);
					$this->interfaces[$isInterface]['protocol'] = $interface[2];
					$this->interfaces[$isInterface]['method'] = $interface[3];
					continue;
				}
				
				if ($isInterface != NULL)
				{
					preg_match('/^[\s]*([\w\d\-]*)[\s]+(.*)$/im', $line, $match);
					$this->interfaces[$isInterface]['iface'][$match[1]] = $match[2];
				}
			}
			elseif (isset($line[0]) && trim($line)[0] == '#')
				$this->comments[] = $line;
			elseif (trim($line) != '')
				$this->others[] = $line;
		}
		
		return true;
	}
	
	private function writeNetworkInterface()
	{
		$fileLines = '';
		
		foreach ($this->interfaces as $interface => $line)
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
						{
							if ($value != NULL)
								$fileLines .= '    '.$key.' '.$value.PHP_EOL;
						}
					}
				}
				
				$fileLines .= PHP_EOL;
			}
		}
		
		foreach ($this->others as $other)
			$fileLines .= trim($other).PHP_EOL;
		
		foreach ($this->comments as $comment)
			$fileLines .= trim($comment).PHP_EOL;
		
		list ($status, $error) = $this->tpl->executeSSH('echo -e '.escapeshellarg($fileLines).' | sudo /bin/su -c "cat > /etc/network/interfaces"');
		
		if ($status == '')
			return $this->readInterfaces();
		
		return false;
	}
	
}
?>