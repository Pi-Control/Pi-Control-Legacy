<?php
if (!defined('PICONTROL')) exit();

function rpi_getRuntime()
{
	$runtime = trim(@shell_exec('cat /proc/uptime | awk -F \'.\' \'{print $1}\''));
	return $runtime;
}

function rpi_getHostname()
{
	$host = trim(@shell_exec('cat /proc/sys/kernel/hostname'));
	return $host;
}

function rpi_getHostAddr()
{
	if (!isset($_SERVER['SERVER_ADDR']))
		return 'unknown';
	
	if (!$ip = $_SERVER['SERVER_ADDR'])
		return gethostbyname($this->getHostname());
	
	return $ip;
}

function rpi_getCoreTemprature()
{
	$file = 85000;
	
	while ($file == 85000)
		$file = @shell_exec('cat /sys/class/thermal/thermal_zone0/temp');
	
	if ($file != false)
		return round((trim($file)/1000), 2);
	
	return 0;
}

function rpi_getCpuClock()
{
	$file = shell_exec('cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq');
	
	if ($file !== false)
		return (int) round(trim($file)/1000);
	
	return 0;
}

function rpi_getCpuMinClock()
{
	$file = shell_exec('cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_min_freq');
	
	if ($file !== false)
		return (int) round(trim($file)/1000);
	
	return 0;
}

function rpi_getCpuMaxClock()
{
	$file = shell_exec('cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_max_freq');
	
	if ($file !== false)
		return (int) round(trim($file)/1000);
	
	return 0;
}

function rpi_getCPUType()
{
	$file = shell_exec('cat /proc/cpuinfo');
	preg_match('#Hardware\s*:\s*([^\s]+)#i', $file, $match);
	
	if (isset($match[1]))
		return $match[1];
	
	return NULL;
}

function rpi_getCpuModel()
{
	$model = trim(@shell_exec('cat /proc/cpuinfo | grep -m 1 "model name" | tr -d " " | cut -d ":" -f 2'));
	return $model;
}

function rpi_getCPULoad($accurate = false, $mulitcore = false)
{
	$return = NULL;
	
	if ($accurate === true)
		$file = shell_exec('cat /proc/stat; sleep 2; echo "##--##"; cat /proc/stat');
	else
		$file = shell_exec('cat /proc/stat; sleep 0.5; echo "##--##"; cat /proc/stat');
	
	$file = explode('##--##', $file);
	
	if (!isset($file[0], $file[1]))
		return NULL;
	
	preg_match_all('/^cpu[0-9]?(.*)$/im', $file[0], $prevCPUStrings);
	preg_match_all('/^cpu[0-9]?(.*)$/im', $file[1], $curCPUStrings);
	
	for ($i = 0; $i < count($prevCPUStrings[0]); $i++)
	{
		$prevCPU = preg_split('/\s+/', $prevCPUStrings[0][$i]);
		$curCPU = preg_split('/\s+/', $curCPUStrings[0][$i]);
		
		if ($prevCPU[0] != 'cpu' && $mulitcore == false)
			break;
		
		
		if (!isset($prevCPU[0], $curCPU[1]) || count($prevCPU) != 11 || count($curCPU) != 11)
			return NULL;
		
		$prevIdle = $prevCPU[4] + $prevCPU[5];
		$curIdle = $curCPU[4] + $curCPU[5];
		
		$prevNonIdle = $prevCPU[1] + $prevCPU[2] + $prevCPU[3] + $prevCPU[6] + $prevCPU[7] + $prevCPU[8];
		$curNonIdle = $curCPU[1] + $curCPU[2] + $curCPU[3] + $curCPU[6] + $curCPU[7] + $curCPU[8];
		
		$prevTotal = $prevIdle + $prevNonIdle;
		$curTotal = $curIdle + $curNonIdle;
		
		$total = $curTotal - $prevTotal;
		$idle = $curIdle - $prevIdle;
		
		if ($mulitcore == true)
			$return[$prevCPU[0]] = (int) round(($total - $idle) / $total * 100);
		else
			$return = (int) round(($total - $idle) / $total * 100);
	}
	
	return $return;
}

function rpi_getDistribution()
{
	$distribution = trim(@shell_exec('cat /etc/issue | cut -d " " -f 1-3'));
	
	if ($distribution == '')
	{
		$distributionString = @shell_exec('cat /etc/*-release | grep PRETTY_NAME');
		
		preg_match('/.*="([\w\s\d\/]*).*"/i', $distributionString, $match);
		
		if (count($match) == 2)
			$distribution = trim($match[1]);
		else
			$distribution = _t('Unbekannt');
	}
	
	return $distribution;
}

function rpi_getKernelVersion()
{
	$kernel = trim(@shell_exec('cat /proc/version | cut -d " " -f 1,3'));
	return $kernel;
}

function rpi_getCountRunningTasks()
{
	$tasks = trim(@shell_exec('ps -auxeaf| wc -l'));
	return $tasks;
}

function rpi_getCountInstalledPackages()
{
	$packages = trim(@shell_exec('dpkg --get-selections | grep -v deinstall | wc -l'));
	return $packages;
}

function rpi_getInstalledPackages()
{
	@exec('dpkg -l | grep ^ii', $packages);
	
	$packages = array_map(function($package) { return preg_split('/[\s]+/', $package, 5); }, $packages);
	return $packages;
}

function rpi_getRpiRevision()
{
	$revision = array();
	$revision[2] = array('revision' => '0002', 'model' => 'B', 'pcb' => '1.0', 'memory' => 256, 'manufacturer' => '');
	$revision[3] = array('revision' => '0003', 'model' => 'B', 'pcb' => '1.0', 'memory' => 256, 'manufacturer' => '');
	$revision[4] = array('revision' => '0004', 'model' => 'B', 'pcb' => '2.0', 'memory' => 256, 'manufacturer' => 'Sony');
	$revision[5] = array('revision' => '0005', 'model' => 'B', 'pcb' => '2.0', 'memory' => 256, 'manufacturer' => 'Qisda');
	$revision[6] = array('revision' => '0006', 'model' => 'B', 'pcb' => '2.0', 'memory' => 256, 'manufacturer' => 'Egoman');
	$revision[7] = array('revision' => '0007', 'model' => 'A', 'pcb' => '2.0', 'memory' => 256, 'manufacturer' => 'Egoman');
	$revision[8] = array('revision' => '0008', 'model' => 'A', 'pcb' => '2.0', 'memory' => 256, 'manufacturer' => 'Sony');
	$revision[9] = array('revision' => '0009', 'model' => 'A', 'pcb' => '2.0', 'memory' => 256, 'manufacturer' => 'Qisda');
	$revision[13] = array('revision' => '000d', 'model' => 'B', 'pcb' => '2.0', 'memory' => 512, 'manufacturer' => 'Egoman');
	$revision[14] = array('revision' => '000e', 'model' => 'B', 'pcb' => '2.0', 'memory' => 512, 'manufacturer' => 'Sony');
	$revision[15] = array('revision' => '000f', 'model' => 'B', 'pcb' => '2.0', 'memory' => 512, 'manufacturer' => 'Qisda');
	$revision[16] = array('revision' => '0010', 'model' => 'B+', 'pcb' => '1.0', 'memory' => 512, 'manufacturer' => 'Sony');
	$revision[17] = array('revision' => '0011', 'model' => 'Compute Module', 'pcb' => '1.0', 'memory' => 512, 'manufacturer' => 'Sony');
	$revision[18] = array('revision' => '0012', 'model' => 'A+', 'pcb' => '1.0', 'memory' => 256, 'manufacturer' => 'Sony');
	$revision[19] = array('revision' => '0013', 'model' => 'B+', 'pcb' => '1.2', 'memory' => 512, 'manufacturer' => 'Embest');
	$revision[20] = array('revision' => '0014', 'model' => 'Compute Module', 'pcb' => '1.0', 'memory' => 512, 'manufacturer' => 'Embest');
	$revision[21] = array('revision' => '0015', 'model' => 'A+', 'pcb' => '1.1', 'memory' => 256, 'manufacturer' => 'Embest');
	
	$revision_model = array(0 => 'A', 1 => 'B', 2 => 'A+', 3 => 'B+', 4 => 'Pi 2 B', 5 => 'Alpha', 6 => 'Compute Module', 7 => 'Zero', 8 => 'Pi 3 B', 9 => 'Zero', 10 => 'Compute Module 3', 12 => 'Zero W');
	$revision_memory = array(0 => 256, 1 => 512, 2 => 1024);
	$revision_manufacturer = array(0 => 'Sony', 1 => 'Egoman', 2 => 'Embest', 3 => 'Sony Japan', 4 => 'Embest');
	
	$file = shell_exec('cat /proc/cpuinfo');
	preg_match('#\nRevision\s*:\s*([\da-f]+)#i', $file, $match);
	
	/*
	 * ######
	 * |||||+- PCB
	 * ||||+- Model
	 * |||+- Model
	 * ||+- Unknown
	 * |+- Manufacturer
	 * +- Memory
	 */
	
	if (isset($match[1]))
	{
		if ($match[1][0] == '1' || $match[1][0] == '2')
			$match[1] = substr($match[1], 1);
		
		if (strlen($match[1]) == 4)
			return $revision[hexdec($match[1])];
		elseif (strlen($match[1]) == 6 && $match[1][0] != 'a' && $match[1][0] != '9')
			return $revision[hexdec(substr($match[1], -4))];
		elseif (strlen($match[1]) == 6)
		{
			return array('revision' => $match[1],
				'model' => $revision_model[hexdec(substr($match[1], 3, 2))],
				'pcb' => '1.'.hexdec(substr($match[1], -1)),
				'memory' => $revision_memory[bindec(substr(decbin(hexdec(substr($match[1], 0, 1))), 1))],
				'manufacturer' => $revision_manufacturer[hexdec(substr($match[1], 1, 1))]);
		}
	}
	
	return NULL;
}

function rpi_getRpiSerial()
{
	$file = shell_exec('cat /proc/cpuinfo');
	preg_match('#\nSerial\s*:\s*([\da-f]+)#i', $file, $match);
	
	return $match[1];
}

function rpi_getMemorySplit()
{
	$rev = $this->getRpiRevision();
	if ($rev >= 7)
	{
		// 512 MB
		$config = @shell_exec('cat /boot/config.txt');
		preg_match('#gpu_mem=([0-9]+)#i', $config, $match);
		$total = intval($match[1]);
		
		if ($total == 16)
			return array('system' => '496 MiB', 'video' => '16 MiB');
		elseif ($total == 32)
			return array('system' => '480 MiB', 'video' => '32 MiB');
		elseif ($total == 64)
			return array('system' => '448 MiB', 'video' => '64 MiB');
		elseif ($total == 128)
			return array('system' => '384 MiB', 'video' => '128 MiB');
		
		return array('system' => '256 MiB', 'video' => '256 MiB');
	}
	
	// 256 MB
	$mem = $this->getMemoryUsage();
	$total = round($mem['total'] / 1024 / 1024, 0);
	
	if ($total <= 128)
		return array('system' => '128 MiB', 'video' => '128 MiB');
	elseif ($total > 128 && $total <= 192)
		return array('system' => '192 MiB', 'video' => '64 MiB');
	elseif ($total > 192 && $total <= 224)
		return array('system' => '224 MiB', 'video' => '32 MiB');
	
	return array('system' => '240 MiB', 'video' => '16 MiB');
}

function rpi_getMemoryUsage()
{
	exec('free -bo 2>/dev/null || free -b', $data);
	
	if (strpos($data[0], 'available') !== false) {
		list($type, $total, $used, $free, $shared, $buffers, $available) = preg_split('#\s+#', $data[1]);
		$usage = (int) round(($total - $available) / $total * 100);
		
		return array('percent' => $usage, 'total' => $total, 'free' => $available, 'used' => ($total - $available));
	}
	
	list($type, $total, $used, $free, $shared, $buffers, $cached) = preg_split('#\s+#', $data[1]);
	$usage = (int) round(($used - $buffers - $cached) / $total * 100);
	
	return array('percent' => $usage, 'total' => $total, 'free' => ($free + $buffers + $cached), 'used' => ($used - $buffers - $cached));
}

function rpi_getSwapUsage()
{
	exec('free -bo 2>/dev/null || free -b', $data);
	list($type, $total, $used, $free) = preg_split('#\s+#', $data[2]);
	$usage = (int) round($used / $total * 100);
	
	return array('percent' => $usage, 'total' => $total, 'free' => $free, 'used' => $used);
}

function multiArraySearch($array, $key, $value)
{
	foreach ($array as $item)
		if (isset($item[$key]) && $item[$key] == $value)
			return true;
	
	return false;
}

function rpi_getMemoryInfo()
{
	exec('df -lT | grep -vE "tmpfs|rootfs|Filesystem|Dateisystem"', $data);
	
	$devices = array();
	$totalSize = 0;
	$usedSize = 0;
	
	foreach ($data as $row)
	{
		list($device, $type, $blocks, $use, $available, $used, $mountpoint) = preg_split('#[\s%]+#i', $row);
		
		if (multiArraySearch($devices, 'device', $device) === false)
		{
			$totalSize += $blocks * 1024;
			$usedSize  += $use * 1024;
		}
		
		$devices[] = array(
						'device'		=> $device,
						'type'			=> $type,
						'total'			=> $blocks * 1024,
						'used'			=> $use * 1024,
						'free'			=> $available * 1024,
						'percent'		=> (int) round(($use * 100 / $blocks)),
						'mountpoint'	=> $mountpoint
		);
	}
	
	usort($devices, function($a, $b)
	{
    	return strcasecmp($a['device'], $b['device']);
	});
	
	$devices[] = array('total' => $totalSize, 'used' => $usedSize, 'free' => $totalSize - $usedSize, 'percent' => (int) round(($usedSize * 100 / $totalSize)));
	
	return $devices;
}

function rpi_getUsbDevices()
{
	exec('lsusb', $data);
	$devices = array();
	
	foreach ($data as $row)
	{
		preg_match('#[0-9a-f]{4}:[0-9a-f]{4}\s+(.+)#i', $row, $match);
		
		if (count($match) == 2)
			$devices[] = trim($match[1]);
		else
			$devices[] = '<'._t('Unbekannt').'>';
	}
	
	return $devices;
}

function rpi_getAllUsers()
{
	function array_orderby()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
				}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
	
	exec('/usr/bin/who --ips', $dataLoggedIn);
	exec('/usr/bin/lastlog | grep -vE "Benutzername|Username" | cut -f 1 -d " "', $dataAllUsers);
	
	$usersLoggedIn = array();
	$usersAll = array();
	
	foreach ($dataLoggedIn as $row)
	{
		$split = preg_split('/\s+/i', $row);
		
		if (count($split) == 6)
			$usersLoggedIn[$split[0]][] = array('port' => $split[1], 'lastLogin' => strtotime($split[2].' '.$split[3].' '.$split[4]), 'lastLoginAddress' => $split[5]);
	}
	
	foreach ($dataAllUsers as $row)
	{
		$userLastLoginInformation = '';
		$userLastLoginInformation = shell_exec('/usr/bin/last -i -f /var/log/wtmp | grep -m 1 "^'.$row.' "');
		
		if ($userLastLoginInformation == '')
			$userLastLoginInformation = shell_exec('/usr/bin/last -i -f /var/log/wtmp.1 | grep -m 1 "^'.$row.' "');
		
		if ($userLastLoginInformation != '')
		{
			$split = preg_split('/\s+/i', $userLastLoginInformation);
			
			$usersAll[] = array('username' => $row,
								'userId' => exec('id -u '.escapeshellarg($row)),
								'groupId' => exec('id -g '.escapeshellarg($row)),
								'port' => $split[1],
								'lastLoginAddress' => $split[2],
								'lastLogin' => strtotime($split[4].' '.$split[5].' '.$split[6]),
								'isLoggedIn' => isset($usersLoggedIn[$row]) ? true : false,
								'loggedIn' => isset($usersLoggedIn[$row]) ? $usersLoggedIn[$row] : array());
		}
		else
		{
			$usersAll[] = array('username' => $row,
								'userId' => exec('id -u '.escapeshellarg($row)),
								'groupId' => exec('id -g '.escapeshellarg($row)),
								'port' => '',
								'lastLoginAddress' => '',
								'lastLogin' => 0,
								'isLoggedIn' => isset($usersLoggedIn[$row]) ? true : false);
		}
	}
	
	$usersAll = array_orderby($usersAll, 'isLoggedIn', SORT_DESC, 'username', SORT_ASC);
	
	return $usersAll;
}
?>