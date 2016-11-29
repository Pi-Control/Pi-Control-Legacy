<?php
if (!defined('PICONTROL')) exit();

// functions.php

/**
 * Setzt Konfigurationswert für config.ini.php.
 *
 * <code>$tpl->setConfig('other.test', 'Wert'); // Weißt der Konfigvariable ['other']['test'] den Wert "Wert" zu.</code>
 *
 * @param string $config Konfigschlüssel
 * @param string $value Konfigwert
 * @return bool
 */

function setConfig($config, $value, $customFile = NULL)
{
	$configPath = CONFIG_PATH;
	$configFileSuffix = '.config.ini.php'; // Standard-Konfig
	
	if ($customFile !== NULL)
		$configPath = $customFile;
	
	$file = explode(':', $config);
	
	if (count($file) != 2)
		return false;
	
	$configFile = $configPath.$file[0].$configFileSuffix;
	
	if (file_exists($configFile) !== true || is_file($configFile) !== true)
	{
		if (!touch($configFile))
			return false;
	}
	
	$configArray = parse_ini_file($configFile, true);
	
	if (!strlen($config) > 0 || !is_string($config))
		return false;
	
	$var = explode('.', $file[1]);
	
	if (count($var) != 2)
		$configArray[$var[0]] = $value;
	else
		$configArray[$var[0]][$var[1]] = $value;
	
	return writeConfig($configArray, $configFile);
}

/**
 * Ermittelt Konfigurationswert aus config.ini.php.
 *
 * <code>$tpl->getConfig('other.test', 'Wert'); // Ermittelt den Wert von Konfigvariable ['other']['test']. Standardwert: "Wert".</code>
 *
 * @param string $config Konfigschlüssel
 * @param string $default Standardwert
 * @return string|int Im Fehlerfall der Standardwert, ansonsten den Konfigwert.
 */

function getConfig($config, $default = NULL, $customFile = NULL)
{
	$configPath = CONFIG_PATH;
	$configFileSuffix = '.config.ini.php'; // Standard-Konfig
	
	if ($customFile !== NULL)
		$configPath = $customFile;
	
	$file = explode(':', $config);
	
	if (count($file) != 1 && count($file) != 2)
		return $default;
	
	$configFile = $configPath.$file[0].$configFileSuffix;
	
	if (file_exists($configFile) !== true || is_file($configFile) !== true)
		return $default;
	
	$configArray = parse_ini_file($configFile, true);
	
	if (!strlen($config) > 0 || !is_string($config))
		return $default;
	
	if (!count($configArray) > 0)
		return $default;
	
	if (isset($file[1]))
	{
		$var = explode('.', $file[1]);
		
		if (count($var) == 1 && isset($configArray[$var[0]]))
			return $configArray[$var[0]];
		elseif (count($var) == 2 && isset($configArray[$var[0]][$var[1]]))
			return $configArray[$var[0]][$var[1]];
	}
	else
	{
		if (isset($configArray))
			return $configArray;
	}
	
	return $default;
}

function removeConfig($config, $customFile = NULL)
{
	$configPath = CONFIG_PATH;
	$configFileSuffix = '.config.ini.php'; // Standard-Konfig
	
	if ($customFile !== NULL)
		$configPath = $customFile;
	
	$file = explode(':', $config);
	
	if (count($file) != 2)
		return false;
	
	$configFile = $configPath.$file[0].$configFileSuffix;
	
	if (file_exists($configFile) !== true || is_file($configFile) !== true)
		return false;
	
	$configArray = parse_ini_file($configFile, true);
	
	if (!strlen($config) > 0 || !is_string($config))
		return false;
	
	$var = explode('.', $file[1]);
	
	if (count($var) == 1)
		unset($configArray[$var[0]]);
	elseif (count($var) == 2)
		unset($configArray[$var[0]][$var[1]]);
	else
		return false;
	
	return writeConfig($configArray, $configFile);
}

/**
 * Schreibt Konfig-Ini-Datei mit neuen Werten.
 *
 * <code>$tpl->writeConfig();</code>
 *
 * @return bool
 */

function writeConfig($configArray, $configFile)
{
	$res = array(';<?php', ';die();');
	
	ksort($configArray);
	
	foreach ($configArray as $key => $val)
	{
		if (is_array($val))
		{
			$res[] = PHP_EOL."[$key]";
			
			foreach ($val as $skey => $sval)
				$res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
		}
		else
			$res[] = PHP_EOL."$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
	}
	
	$res[] = ';?>';
	
	if (file_exists($configFile) !== true || is_file($configFile) !== true || is_writeable($configFile) !== true)
		return false;
	
	if ($fp = fopen($configFile, 'w'))
	{
		$startTime = microtime();
		do
		{
			$canWrite = flock($fp, LOCK_EX);
			
			// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			if (!$canWrite)
				usleep(round(rand(0, 100)*1000));
		} while ((!$canWrite) && ((microtime()-$startTime) < 1000));
		
		// file was locked so now we can store information
		if ($canWrite)
		{
			fwrite($fp, implode(PHP_EOL, $res));
			flock($fp, LOCK_UN);
		}
		fclose($fp);
	}
	
	return true;
}

/**
 * Übersetzt Text in andere Sprache.
 *
 * <code>$tpl->_t('Hallo %s!', 'Welt'); // Rückgabe: Hallo Welt!</code>
 *
 * @param string $text Text
 * @param string|int|float $args[] Argumente
 * @return string
 */

function _t()
{
	global $globalLanguage, $globalLanguageArray;
	
	$args = func_get_args();
	$lang = $globalLanguage;
	$langFile = LANGUAGE_PATH.$lang.'.php';
	
	if (empty($globalLanguageArray) && file_exists($langFile) === true && is_file($langFile) === true)
	{
		include $langFile;
		$globalLanguageArray = $langArray;
	}
	
	$checksum = substr(md5($args[0]), 0, 8);
	
	if (is_numeric($checksum))
		$checksum[7] = 'z';
	
	if (isset($globalLanguageArray[$checksum]) && $lang != 'de')
		$args[0] = $globalLanguageArray[$checksum];
	
	return call_user_func_array('sprintf', $args);
}

/**
 * Übersetzt Text in andere Sprache und gibt ihn anschließend aus.
 *
 * <code>$tpl->_e('Hallo %s!', 'Welt'); // Ausgabe: Hallo Welt!</code>
 *
 * @param string $text Text
 * @param string|int|float $args[] Argumente
 * @return bool Ausgabe erfolgt mit "echo".
 */

function _e()
{
	global $globalLanguage, $globalLanguageArray;
	
	$args = func_get_args();
	$lang = $globalLanguage;
	$langFile = LANGUAGE_PATH.$lang.'.php';
	
	if (empty($globalLanguageArray) && file_exists($langFile) === true && is_file($langFile) === true)
	{
		include $langFile;
		$globalLanguageArray = $langArray;
	}
	
	$checksum = substr(md5($args[0]), 0, 8);
	
	if (is_numeric($checksum))
		$checksum[7] = 'z';
	
	if (isset($globalLanguageArray[$checksum]) && $lang != 'de')
		$args[0] = $globalLanguageArray[$checksum];
	
	echo call_user_func_array('sprintf', $args);
	
	return true;
}

function sizeUnit($size, $fixUnit = NULL)
{
	if ($size == '')
		$size = 0;
	
	if (($size < 1024 && !isset($fixUnit)) ||  (isset($fixUnit) && $fixUnit == 'B'))
		return number_format($size, 0, ',', '').' Byte';
	elseif (($size < 1024000 && !isset($fixUnit)) || (isset($fixUnit) && $fixUnit == 'K'))
		return number_format(round($size/1024,2), 2, ',', '').' KB';
	elseif (($size < 1048576000 && !isset($fixUnit)) || (isset($fixUnit) && $fixUnit == 'M'))
		return number_format(round($size/1048576,2), 2, ',', '').' MB';
	elseif (($size < 1073741824000 && !isset($fixUnit)) || (isset($fixUnit) && $fixUnit == 'G'))
		return number_format(round($size/1073741824,2), 2, ',', '').' GB';
	elseif (($size < 1099511627776000 && !isset($fixUnit)) || (isset($fixUnit) && $fixUnit == 'T'))
		return number_format(round($size/1099511627776,2), 2, ',', '').' TB';
}

function return_bytes($size)
{
	$size = trim($size);
	$last = strtolower($size[strlen($size)-1]);
	
	switch ($last)
	{
		case 'g':
			$size *= 1024;
		case 'm':
			$size *= 1024;
		case 'k':
			$size *= 1024;
	}
	return $size;
}

function numberFormat($value, $precision = 2, $delimiter = ',')
{
	return number_format($value, $precision, $delimiter, '');
}

function getFolderSize($folder_path, $folder_size = 0)
{
	if (!is_dir($folder_path))
		$folder_size += filesize($folder_path);
	else
	{
		$folder_dir = opendir($folder_path);
		while ($folder_file = readdir($folder_dir))
		{
			if (is_file($folder_path.'/'.$folder_file))
				$folder_size += filesize($folder_path.'/'.$folder_file);
			if (is_dir($folder_path.'/'.$folder_file) && $folder_file != '.' && $folder_file != '..')
				$folder_size = getFolderSize($folder_path.'/'.$folder_file, $folder_size);
		}
	}
	return($folder_size);
}

function formatTime($time, $type = 'd.m.Y H:i')
{
	if ($time == '')
		return false;
	
	return date($type, $time);
}

if (!function_exists('array_column'))
{
	function array_column($array, $columnName)
	{
		return array_map(function($element) use($columnName) { return $element[$columnName]; }, $array);
	}
}

function checkUpdate()
{
	global $config, $globalLanguage;
	
	$lang = $globalLanguage;
	
	if (!class_exists('cURL'))
		(include LIBRARY_PATH.'curl/curl.class.php');
	
	$curl = new cURL($config['url']['update']);
	$curl->execute();
	
	if ($curl->getStatusCode() != '200')
		return $curl->getStatusCode();
	
	if ($curl->getResult($data) != JSON_ERROR_NONE)
		return 1;
	
	if (!isset($data['versions'], $data['latest']))
		return 1;
	
	if ($data['latest']['versioncode'] > $config['version']['versioncode'])
	{
		$currentUpdateKey = array_search($config['version']['versioncode']+1, array_column($data['versions'], 'versioncode'));
		
		if (isset($data['versions'][$currentUpdateKey]['changelog'][$lang]))
			$data['versions'][$currentUpdateKey]['changelog'] = $data['versions'][$currentUpdateKey]['changelog'][$lang];
		else
			$data['versions'][$currentUpdateKey]['changelog'] = current($data['versions'][$currentUpdateKey]['changelog']);
		
		return $data['versions'][$currentUpdateKey];
	}
	else
		return 0;
}

function getDateFormat($time)
{
	$day = floor($time / 60 / 60 / 24);
	$day = ($day < 10) ? '0'.$day : $day;
	$day = ($day == 1) ? '01 Tag ' : $day.' Tage ';
	$day = ($day == '00 Tage ') ? '' : $day;
	$hour = floor($time / 60 / 60 % 24);
	$hour = ($hour < 10) ? '0'.$hour : $hour;
	$hour = ($hour == 24) ? '00' : $hour;
	$minute = floor($time / 60 % 60);
	$minute = ($minute < 10) ? '0'.$minute : $minute;
	$second = floor($time % 60);
	$second = ($second < 10) ? '0'.$second : $second;
	
	return $day.$hour.':'.$minute.':'.$second;
}

function getImageFromSignal($signal)
{
	if ($signal <= 10)
		return '<span class="svg-network-signal-00"></span>';
	elseif ($signal <= 25)
		return '<span class="svg-network-signal-25"></span>';
	elseif ($signal <= 50)
		return '<span class="svg-network-signal-50"></span>';
	elseif ($signal <= 75)
		return '<span class="svg-network-signal-75"></span>';
	elseif ($signal <= 100)
		return '<span class="svg-network-signal-100"></span>';
	else
		return false;
}

function getAllNetworkConnections()
{
	$shell_string = '';
	$output = array();
	
	exec('/sbin/ifconfig | grep -E -o "^[[:alnum:][:punct:]]*" | grep -E -v "(lo)"', $networkInterfaces);

	foreach ($networkInterfaces as $interface)
		$shell_string .= '/sbin/ifconfig '.$interface.(($networkInterfaces[count($networkInterfaces)-1] != $interface) ? ' && echo "-#-" && ' : '');
		
	$streamInterfaces = explode('-#-', shell_exec($shell_string));
	
	foreach ($streamInterfaces as $streamInterface)
	{
		$wirelessOption = '';
		
		$output0 = $networkInterfaces[count($output)];
		$output1 = trim(strtoupper(substr($streamInterface, strpos($streamInterface, 'HWaddr', 0) + 7, 17)));
		$output2 = trim(substr($streamInterface, strpos($streamInterface, 'inet addr:', 0) + 10, strpos($streamInterface, 'Bcast:', 0) - strpos($streamInterface, 'inet addr:', 0) - 10));
		
		if (strlen($output2) >= 50)
			$output2 = 0;
		
		preg_match('#RX bytes:([\d]+)#', $streamInterface, $match_rx);
		preg_match('#TX bytes:([\d]+)#', $streamInterface, $match_tx);
		
		$output3 = $match_rx[1];
		$output4 = $match_tx[1];
		
		if (substr($output0, 0, 4) == 'wlan')
		{
			$streamWirelessInterface = shell_exec('/sbin/iwconfig '.$output0);
			
			if (0 == substr_count($streamWirelessInterface, 'Not-Associated'))
			{
				$posConfig_start = @strpos($streamWirelessInterface, 'ESSID:"', 0) + 7;
				$posConfig_end = @strpos($streamWirelessInterface, '"', $posConfig_start);
				$wirelessOption['ssid'] = trim(substr($streamWirelessInterface, $posConfig_start, ($posConfig_end - $posConfig_start)));
				
				$posConfig_start = @strpos($streamWirelessInterface, 'Access Point:', 0) + 13;
				$posConfig_end = @strpos($streamWirelessInterface, 'Bit Rate', $posConfig_start);
				$wirelessOption['mac'] = trim(substr($streamWirelessInterface, $posConfig_start, ($posConfig_end - $posConfig_start)));
				
				$posConfig_start = @strpos($streamWirelessInterface, 'Signal level=', 0) + 13;
				$posConfig_end = @strpos($streamWirelessInterface, '/100', $posConfig_start);
				$wirelessOption['signal'] = trim(substr($streamWirelessInterface, $posConfig_start, ($posConfig_end - $posConfig_start)));
			}
		}
		
		$output[] = array('interface' => $output0, 'mac' => $output1, 'ip' => $output2, 'sent' => $output4, 'receive' => $output3, 'option' => $wirelessOption);
	}
	
	return $output;
}

function scanAccessPoints($networkConnections, $ssh = false)
{
	global $tpl;
	
	$wlan = array();
	
	foreach ($networkConnections as $interface)
	{
		if (substr($interface['interface'], 0, 4) != 'wlan')
			continue;
			
		$wlan[$interface['interface']] = array();
		
		if ($ssh == true)
			list ($streamWlan, ) = $tpl->executeSSH('sudo /sbin/iwlist '.escapeshellarg($interface['interface']).' scan');
		else
			$streamWlan = shell_exec('/sbin/iwlist '.escapeshellarg($interface['interface']).' scan');
		
		for ($i = 1; $i <= substr_count($streamWlan, 'ESSID:"'); $i += 1)
		{
			$posCell_start = @strpos($streamWlan, 'Cell '.(($i < 10) ? '0' : '').$i.' - Address:', 0) + 19;
			$posCell_end = @strpos($streamWlan, 'Cell '.((($i+1) < 10) ? '0' : '').($i+1), $posCell_start);
			if ($posCell_end === false)
				$posCell_end = strlen($streamWlan);
			
			$string = substr($streamWlan, $posCell_start, ($posCell_end - $posCell_start));
			
			$posConfig_start = @strpos($string, 'ESSID:"', 0) + 7;
			$posConfig_end = @strpos($string, '"', $posConfig_start);
			$wirelessOption['ssid'] = trim(substr($string, $posConfig_start, ($posConfig_end - $posConfig_start)));
			
			$wirelessOption['mac'] = substr(trim($string), 0, 17);
			
			$posConfig_start = @strpos($string, 'Frequency:', 0) + 10;
			$posConfig_end = @strpos($string, 'Channel', $posConfig_start);
			$wirelessOption['channel'] = trim(str_replace(')', '', substr($string, $posConfig_end+8, 3)));
			
			$posConfig_start = @strpos($string, 'Signal level=', 0) + 13;
			if (strpos(substr($string, $posConfig_start, 20), 'dBm'))
				$posConfig_end = @strpos($string, 'dBm', $posConfig_start);
			else
				$posConfig_end = @strpos($string, '/100', $posConfig_start);
			
			$wirelessOption['signal'] = trim(substr($string, $posConfig_start, ($posConfig_end - $posConfig_start)));
			
			if (strpos(substr($string, $posConfig_start, 20), 'dBm'))
			{
				if ($wirelessOption['signal'] <= -100)
					$wirelessOption['signal'] = 0;
				elseif($wirelessOption['signal'] >= -50)
					$wirelessOption['signal'] = 100;
				else
					$wirelessOption['signal'] = 2 * ($wirelessOption['signal'] + 100);
			}
			
			$posConfig_start = @strpos($string, 'IE: IEEE', 0) + 7;
			$posConfig_end = @strpos($string, '/', $posConfig_start);
			$wirelessOption['encryption'] = trim(substr($string, $posConfig_end+1, 4));
			if (substr($wirelessOption['encryption'], 0, 1) != 'W')
				$wirelessOption['encryption'] = '-';
			
			$wlan[$interface['interface']][] = $wirelessOption;
		}
	}
	
	return $wlan;
}

function formatDevideToName($name)
{
	if (substr($name, 0, 3) == 'eth')
		return 'Ethernet';
	elseif (substr($name, 0, 4) == 'wlan')
		return 'WLAN';
	else
		return $name;
}

function urlIsPublic($url)
{
	$ip = gethostbyname($url);
	$long = ip2long($ip);
	
	if (($long >= 167772160 && $long <= 184549375) || ($long >= -1408237568 && $long <= -1407188993) || ($long >= -1062731776 && $long <= -1062666241) || ($long >= 2130706432 && $long <= 2147483647) || $long == -1)
		return false;
	
	return true;
}

function showSettingsIcon($url)
{
	echo '<div><a href="'.$url.'" class="settings-shortcut-icon"><img src="public_html/img/gear-icon.svg" alt="'._t('Einstellungen').'" title="'._t('Einstellungen').'" /></a></div>'.PHP_EOL;
}

function showGoBackIcon($url)
{
	echo '<div><a href="'.$url.'" class="go-back-icon"><img src="public_html/img/arrow-icon.svg" alt="'._t('Zur&uuml;ck').'" title="'._t('Zur&uuml;ck').'" /></a></div>'.PHP_EOL;
}

function getDirectory($folder_)
{
	$folderArray = array();
	$fileArray = array();
	$folder = array();
	$file = array();
	
	foreach (@scandir($folder_) as $file_)
	{
		if ($file_[0] != '.')
		{
			if (is_dir($folder_.'/'.$file_))
			{
				$folderArray[] = $file_;
				$fileArray[] = $file_;
			}
		}
	}
	
	if (isset($folderArray))
		foreach ($folderArray as $row)
			$folder[] = $row;
	
	if (isset($fileArray))
		foreach ($fileArray as $row)
			$file[] = $row;
	
	return array ($folder, $file);
}

function getAllFiles($folder_)
{
	$folderArray = array();
	$fileArray = array();
	$folder = array();
	$file = array();
	$errorArray = array();
	
	foreach (@scandir($folder_) as $file_)
		if ($file_[0] != '.')
			if (is_dir($folder_.'/'.$file_))
				$folderArray[] = $file_;
			else
				$fileArray[] = $file_;
	
	if (isset($folderArray))
	{
		foreach ($folderArray as $row)
		{
			list ($file_return, $error_log) = getAllFiles($folder_.'/'.$row);
			$file[$row] = $file_return;
			
			if (is_writeable($folder_.'/'.$row) !== true)
				$errorArray[] = $folder_.'/'.$row.'/';

			$errorArray = array_merge($errorArray, $error_log);
		}
	}
	
	if (isset($fileArray))
	{
		foreach ($fileArray as $row)
		{
			$file[] = $row;
			
			if (is_writeable($folder_.'/'.$row) !== true)
				$errorArray[] = $folder_.'/'.$row;
		}
	}
	
	return array($file, $errorArray);
}

function deleteFolder($folder)
{
	chmod($folder, 0777);
	
	if (is_dir($folder))
	{
		$handle = opendir($folder);
		while ($filename = readdir($handle))
			if ($filename != '.' && $filename != '..')
				deleteFolder($folder.'/'.$filename);
		
		closedir($handle);
		rmdir($folder);
	}
	else
		unlink($folder);
}

function checkInternetConnection()
{
	if (function_exists('fsockopen') && ini_get('allow_url_fopen') !== false)
	{
		if (!$sock = @fsockopen('www.google.com', 80, $num, $error, 5))
			return false; // Raspberry Pi is not connected to internet
		else
			return true;
	}
	else
		return false;
}

function getURLLangParam($echo = false, $html = true, $first = false)
{
	global $globalLanguage;
	
	$param = '&';
	
	if ($html === true)
		$param .= 'amp;';
	
	if ($first !== false)
		$param = '?';
	
	$param .= 'lang='.$globalLanguage;
	
	if ($echo !== false)
		echo $param;
	
	return $param;
}

function showHelper($url, $extern = false)
{
	global $config;
	
	if ($extern === false)
		$url = $config['url']['help'].'?s=view&amp;i='.$url.getURLLangParam();
	
	return '<a href="'.$url.'" title="'._t('Klicke f&uuml;r Hilfe').'" target="_blank" class="helper">&nbsp;</a>';
}

function addCronToCrontab($cronEntry, $ssh)
{
	exec('cat /etc/crontab', $lines);
	
	$newFile = '';
	$lineCount = 0;
	$lastLine = count($lines)-1;
	$secondLastLine = count($lines)-2;
	$hashtag = 0;
	$hashtagLine = 0;
	
	if (!in_array($cronEntry, $lines))
	{
		if (substr(trim($lines[$lastLine]), 0, 1) == '')
		{
			if (substr(trim($lines[$secondLastLine]), 0, 1) == '#')
			{
				$hashtag = 1;
				$hashtagLine = $secondLastLine;
			}
			else
			{
				$hashtag = 0;
				$hashtagLine = $lastLine;
			}
		}
		
		if (substr(trim($lines[$lastLine]), 0, 1) == '#')
		{
			$hashtag = 2;
			$hashtagLine = $lastLine;
		}
		
		foreach ($lines as $line)
		{
			if ($lineCount == $hashtagLine)
			{
				if ($hashtag == 0)
				{
					$newFile .= $cronEntry."\n";
					$newFile .= '#';
				}
				elseif ($hashtag == 1)
					$newFile .= $cronEntry."\n";
				elseif ($hashtag == 2)
					$newFile .= $cronEntry."\n";
			}
			
			$newFile .= $lines[$lineCount]."\n";
			$lineCount += 1;
		}
		
		if (file_exists(TEMP_PATH.'crontab.tmp.php') && is_file(TEMP_PATH.'crontab.tmp.php'))
			unlink(TEMP_PATH.'crontab.tmp.php');
		
		if (($file = fopen(TEMP_PATH.'crontab.tmp.php', 'w+')))
		{
			if (!fwrite($file, $newFile))
				return 4;
		}
		else
			return 3;
		
		if (($stream = ssh2_scp_send($ssh, TEMP_PATH.'crontab.tmp.php', '/etc/crontab')))
		{
			unlink(TEMP_PATH.'crontab.tmp.php');
			return 0;
		}
		else
			return 1;
	}
	else
		return 2;
}

function getWeatherIconYahoo($icon)
{
	switch ($icon)
	{
		case 32:
		case 36:
			return '01d';
		case 31:
			return '01n';
		case 30:
		case 34:
			return '02d';
		case 29:
		case 33:
			return '02n';
		case 26:
		case 44:
			return '03';
		case 27:
		case 28:
			return '04';
		case 1:
		case 2:
		case 9:
		case 11:
		case 12:
		case 40:
			return '09';
		case 0:
		case 3:
		case 4:
		case 37:
		case 38:
		case 39:
		case 45:
		case 47:
			return '11';
		case 5:
		case 6:
		case 7:
		case 8:
		case 10:
		case 13:
		case 14:
		case 15:
		case 16:
		case 17:
		case 18:
		case 35:
		case 41:
		case 42:
		case 43:
		case 46:
			return '13';
		case 19:
		case 20:
		case 21:
		case 22:
		case 23:
		case 24:
		case 25:
			return '50';
		default:
			return '01d';
	}
}

function getWeatherIconOpenWeatherMap($icon)
{
	switch ($icon)
	{
		case '01d':
			return '01d';
		case '01n':
			return '01n';
		case '02d':
			return '02d';
		case '02n':
			return '02n';
		case '03d':
		case '03n':
			return '03';
		case '04d':
		case '04n':
			return '04';
		case '09d':
		case '09n':
			return '09';
		case '10d':
			return '10d';
		case '10n':
			return '10n';
		case '11d':
		case '11n':
			return '11';
		case '13d':
		case '13n':
			return '13';
		case '50d':
		case '50n':
			return '50';
		default:
			return '01d';
	}
}

function getWeather()
{
	if (!class_exists('cURL'))
		(include LIBRARY_PATH.'curl/curl.class.php');
	
	$service = getConfig('main:weather.service', 'openweathermap');
	$serviceToken = getConfig('main:weather.serviceToken', '');
	$country = getConfig('main:weather.country', 'germany');
	$type = getConfig('main:weather.type', 'postcode');
	$postcode = getConfig('main:weather.postcode', '');
	$city = getConfig('main:weather.city', '');
	
	if ($serviceToken == '' && $service == 'openweathermap')
		return 3;
	
	if ($postcode == '' && $city == '')
		return 2;
		
	if (($type == 'postcode' && $postcode == '') || ($type == 'city' && $city == ''))
		return 0;
	
	if ($type == 'postcode')
		$location = $postcode;
	else
		$location = $city;
	
	$output = array();
	
	if ($service == 'openweathermap')
	{
		$curl = new cURL('http://api.openweathermap.org/data/2.5/weather?q='.$location.','.$country.'&units=metric&lang=de&appid='.$serviceToken);
		$curl->execute();
		
		if ($curl->getStatusCode() != '200')
			return $curl->getStatusCode();
		
		if ($curl->getResult($data) != JSON_ERROR_NONE)
			return 1;
		
		if (!isset($data['name']) || !isset($data['weather']))
			return 1;
		
		$output['service'] = 'openweathermap';
		$output['city'] = $data['name'];
		$output['country'] = $data['sys']['country'];
		$output['temp'] = str_replace('.', ',' , round($data['main']['temp']));
		$output['temp_min'] = str_replace('.', ',' , round($data['main']['temp_min']));
		$output['temp_max'] = str_replace('.', ',' , round($data['main']['temp_max']));
		$output['humidity'] = $data['main']['humidity'];
		$output['wind'] = str_replace('.', ',' , round($data['wind']['speed']));
		$output['icon'] = getWeatherIconOpenWeatherMap($data['weather'][0]['icon']);
		$output['description'] = $data['weather'][0]['description'];
		
		if (empty($data['name']))
		{
			$curl = new cURL('http://api.openweathermap.org/data/2.5/weather?q='.$location.','.$country.'&appid='.$serviceToken);
			$curl->execute();
			
			if ($curl->getStatusCode() != '200')
				return $curl->getStatusCode();
			
			if ($curl->getResult($data) != JSON_ERROR_NONE)
				return 1;
			
			if (!isset($data['name']) || !isset($data['weather']))
				return 1;
			
			$output['city'] = $data['name'];
			$output['country'] = $data['sys']['country'];
		}
	}
	else
	{
		$yahooApiUrl = 'https://query.yahooapis.com/v1/public/yql';
		$yqlQuery = 'select location, wind, atmosphere, item.condition, item.forecast from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$location.', '.$country.'") AND u=\'c\' | truncate(count=1)';
		$yqlQueryUrl = $yahooApiUrl.'?q='.urlencode($yqlQuery).'&format=json';
		
		$curl = new cURL($yqlQueryUrl);
		$curl->execute();
		
		if ($curl->getStatusCode() != '200')
			return $curl->getStatusCode();
		
		if ($curl->getResult($data) != JSON_ERROR_NONE)
			return 1;
		
		if (!isset($data['query']['results']['channel']))
			return 1;
		
		$data = $data['query']['results']['channel'];
		
		$output['service'] = 'yahoo';
		$output['city'] = $data['location']['city'];
		$output['country'] = $data['location']['country'];
		$output['temp'] = str_replace('.', ',' , round($data['item']['condition']['temp']));
		$output['temp_min'] = str_replace('.', ',' , round($data['item']['forecast']['low']));
		$output['temp_max'] = str_replace('.', ',' , round($data['item']['forecast']['high']));
		$output['humidity'] = $data['atmosphere']['humidity'];
		$output['wind'] = str_replace('.', ',' , round($data['wind']['speed']));
		$output['icon'] = getWeatherIconYahoo($data['item']['condition']['code']);
		$output['description'] = $data['item']['condition']['text'];
	}
		
	return $output;
}

function array_sort($array, $on, $order = SORT_ASC)
{
	$new_array = array();
	$sortable_array = array();

	if (count($array) > 0)
	{
		foreach ($array as $k => $v)
		{
			if (is_array($v))
			{
				foreach ($v as $k2 => $v2)
				{
					if ($k2 == $on)
						$sortable_array[$k] = $v2;
				}
			}
			else
				$sortable_array[$k] = $v;
		}

		switch ($order)
		{
			case SORT_ASC:
				asort($sortable_array);
				break;
			case SORT_DESC:
				arsort($sortable_array);
				break;
		}

		foreach ($sortable_array as $k => $v)
			$new_array[$k] = $array[$k];
	}
	
	return $new_array;
}

function generateUniqId($length = 16, $upper = true)
{
	$random1 = rand(1, 1000);
	$random2 = rand(1, 1000);
	$random3 = rand(1, 1000);
	
	$random11 = 'random'.rand(1, 3);
	$random12 = 'random'.rand(1, 3);
	$random13 = 'random'.rand(1, 3);
	
	$random = md5($$random11 - $$random12 + $$random13);
	$microtime = md5(microtime(true));
	
	$uniqid = substr(md5($random.$microtime.uniqid()), 0, $length);
	
	return ($upper !== true) ? $uniqid : strtoupper($uniqid);
}

function arraySort($array, $on, $order = SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();
	
    if (count($array) > 0)
	{
        foreach ($array as $k => $v)
		{
            if (is_array($v))
			{
                foreach ($v as $k2 => $v2)
				{
                    if ($k2 == $on)
                        $sortable_array[$k] = $v2;
                }
            }
			else
                $sortable_array[$k] = $v;
        }

        switch ($order)
		{
            case SORT_ASC:
            asort($sortable_array);
            	break;
            case SORT_DESC:
            arsort($sortable_array);
            	break;
        }

        foreach ($sortable_array as $k => $v)
            $new_array[$k] = $array[$k];
    }

    return $new_array;
}

function ipInRange($ip, $ranges)
{
	if (!is_array($ranges))
		$ranges[] = $ranges;
	
	foreach ($ranges as $range)
	{
		$return = false;
		
		if (strpos($range, '/') !== false)
		{
			list($range, $netmask) = explode('/', $range, 2);
			
			$blocks = explode('.', $range);
			for ($i = count($blocks); $i < 4; $i++)
			$blocks[] = '0';
			
			$rangeLong = ip2long(implode('.', $blocks));
			$ipLong = ip2long($ip);
			
			$wildcardLong = pow(2, (32 - $netmask)) - 1;
			$netmaskLong = ~ $wildcardLong;
			
			$return = (($ipLong & $netmaskLong) == ($rangeLong & $netmaskLong));
		}
		else
		{
			if (strpos($range, '*') !== false)
			{
				$lower = str_replace('*', '0', $range);
				$upper = str_replace('*', '255', $range);
				$range = $lower.'-'.$upper;
			}
			
			if (strpos($range, '-') !== false)
			{
				list ($lower, $upper) = explode('-', $range, 2);
				$lowerLong = (float) sprintf('%u', ip2long($lower));
				$upperLong = (float) sprintf('%u', ip2long($upper));
				$ipLong = (float) sprintf('%u', ip2long($ip));
				$return =  (($ipLong >= $lowerLong) && ($ipLong <= $upperLong));
			}
			
			if (filter_var($range, FILTER_VALIDATE_IP))
			{
				$rangeLong = ip2long($range);
				$ipLong = ip2long($ip);
				$return =  ($ipLong == $rangeLong);
			}
		}
		
		if ($return == true)
			return true;
	}
	
	return false;
}

function getLanguageFromIso($isoCode)
{
	if (empty($isoCode))
		return false;
	
	switch ($isoCode)
	{
		case 'de': return _t('Deutsch');
		case 'en': return _t('Englisch');
	}
	
	return false;
}

function getTranslatedArrayForJs($translations)
{
	if (!is_array($translations))
		return false;
	
	$output = array();
	
	foreach ($translations as $translation)
		$output[$translation] = _t($translation);
	
	return $output;
}
?>