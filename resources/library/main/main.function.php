<?php
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
		return false;
	
	$configArray = parse_ini_file($configFile, true);
	
	if (!strlen($config) > 0 || !is_string($config))
		return false;
	
	$var = explode('.', $file[1]);
	
	if (count($var) != 2)
		return false;
	
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
	
	if (count($file) != 2)
		return $default;
	
	$configFile = $configPath.$file[0].$configFileSuffix;
	
	if (file_exists($configFile) !== true || is_file($configFile) !== true)
		return $default;
	
	$configArray = parse_ini_file($configFile, true);
	
	if (!strlen($config) > 0 || !is_string($config))
		return $default;
	
	if (!count($configArray) > 0)
		return $default;
		
	$var = explode('.', $file[1]);
	
	if (count($var) == 1 && isset($configArray[$var[0]]))
		return $configArray[$var[0]];
	elseif (count($var) == 2 && isset($configArray[$var[0]][$var[1]]))
		return $configArray[$var[0]][$var[1]];
	else
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
	global $globalLanguage;
	
	$args = func_get_args();
	//$lang = getConfig('other.language', 'de');
	$lang = $globalLanguage;
	$langFile = LANGUAGE_PATH.'/'.$lang.'.php';
	
	if (file_exists($langFile) === true && is_file($langFile) === true)
		include $langFile;
	
	if (isset($langArray[$args[0]]) && $lang != 'de')
		$args[0] = $langArray[$args[0]];
	
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
	global $globalLanguage;
	
	$args = func_get_args();
	//$lang = getConfig('other.language', 'de');
	$lang = $globalLanguage;
	$langFile = LANGUAGE_PATH.'/'.$lang.'.php';
	
	if (file_exists($langFile) === true && is_file($langFile) === true)
		include $langFile;
	
	if (isset($langArray[$args[0]]) && $lang != 'de')
		$args[0] = $langArray[$args[0]];
	
	echo call_user_func_array('sprintf', $args);
	
	return true;
}

function sizeUnit($size)
{
	if ($size == '')
		$size = 0;
	
	if ($size < 1024)
		return number_format($size, 0, ',', '').' Byte';
	elseif ($size < 1024000)
		return number_format(round($size/1024,2), 2, ',', '').' KB';
	elseif ($size < 1048576000)
		return number_format(round($size/1048576,2), 2, ',', '').' MB';
	elseif ($size < 1073741824000)
		return number_format(round($size/1073741824,2), 2, ',', '').' GB';
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

function checkUpdate($what = '')
{
	global $config;
	
	if (function_exists('fsockopen') && ini_get('allow_url_fopen') !== false)
	{
		if (!$sock = @fsockopen('www.google.com', 80, $num, $error, 5))
			return 3; // Raspberry Pi is not connected to internet
		else
		{
			if ($xml = simplexml_load_file($config['urls']['updateUrl']))
			{
				$output = '';
				$check = '';
				$i = 0;
		
				foreach($xml as $data)
				{
					if ($data->versioncode == $config['versions']['versioncode']+1)
					{
						$check = 'update';
						break;
					}
					else
						$check = 'no update';
					
					$i++;
				}
				
				if ($check == 'update')
				{
					$output = '';
					if ($what == 'log')
						$output = (string) nl2br($xml->update[$i]->log);
					elseif ($what == 'filename')
						$output = (string) $xml->update[$i]->filename;
					elseif ($what == 'filesize')
						$output = (string) $xml->update[$i]->filesize;
					elseif ($what == 'checksum')
						$output = (string) $xml->update[$i]->checksum;
					elseif ($what == 'version')
						$output = (string) $xml->update[$i]->version;
					elseif ($what == 'versioncode')
						$output = (string) $xml->update[$i]->versioncode;
					else
						$output = array('version' => (string) $xml->update[$i]->version,
										'versioncode' => (string) $xml->update[$i]->versioncode,
										'filename' => (string) $xml->update[$i]->filename,
										'filesize' => (string) $xml->update[$i]->filesize,
										'checksum' => (string) $xml->update[$i]->checksum,
										'log' => (string) nl2br($xml->update[$i]->log),
										'date' => (string) $xml->update[$i]->date);
					
					return $output;
				}
				elseif ($check == 'no update')
					return 0;
				else
					return 1;
			}
			else
				return 2;
		}
	}
	else
		return 4; // Function is not enabled
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
		return '<img src="public_html/img/nm_signal_00.png" />';
	elseif ($signal <= 25)
		return '<img src="public_html/img/nm_signal_25.png" />';
	elseif ($signal <= 50)
		return '<img src="public_html/img/nm_signal_50.png" />';
	elseif ($signal <= 75)
		return '<img src="public_html/img/nm_signal_75.png" />';
	elseif ($signal <= 100)
		return '<img src="public_html/img/nm_signal_100.png" />';
	else
		return false;
}

function getAllNetworkConnections()
{
	$shell_string = '';
	$output = array();
	
	exec('/sbin/ifconfig | grep -E -o "^[[:alnum:]]*" | grep -E -v "(lo)"', $networkInterfaces);

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

function scanAccessPoints($networkConnections, $ssh = NULL)
{
	$wlan = array();
	
	foreach ($networkConnections as $interface)
	{
		if (substr($interface['interface'], 0, 4) != 'wlan')
			continue;
			
		$wlan[$interface['interface']] = array();
		
		if (empty($ssh))
			$streamWlan = shell_exec('/sbin/iwlist '.$interface['interface'].' scan');
		else
		{
			if ($stream = ssh2_exec($ssh, '/sbin/iwlist '.$interface['interface'].' scan'))
			{
				stream_set_blocking($stream, true);
				$streamWlan = stream_get_contents($stream);
			}
		}
		
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
	echo '<a href="'.$url.'" class="settings-shortcut-icon"><img src="public_html/img/gear.svg" alt="'._t('Einstellungen').'" title="'._t('Einstellungen').'" /></a>'.PHP_EOL;
}

function showGoBackIcon($url)
{
	echo '<a href="'.$url.'" class="go-back-icon"><img src="public_html/img/go_back_icon2.png" alt="'._t('Zurück').'" title="'._t('Zurück').'" /></a>';
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

function delete($folder)
{
	chmod($folder, 0777);
	
	if (is_dir($folder))
	{
		$handle = opendir($folder);
		while ($filename = readdir($handle))
			if ($filename != '.' && $filename != '..')
				delete($folder.'/'.$filename);
		
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

function showHelper($url, $extern = false)
{
	global $config;
	
	if ($extern === false)
		$url = $config['urls']['helpUrl'].'#'.$url;
	
	return '<a href="'.$url.'" title="Klicke für Hilfe" target="_blank" class="helper">&nbsp;</a>';
}

function addCronToCrontab($cron_entry, $ssh)
{
	exec('cat /etc/crontab', $lines);
	$new_file = '';
	$line_count = 0;
	$last_line = count($lines)-1;
	$second_last_line = count($lines)-2;
	$hashtag = 0;
	$hashtag_line = 0;
	
	if (!in_array($cron_entry, $lines))
	{
		if (substr(trim($lines[$last_line]), 0, 1) == '')
		{
			if (substr(trim($lines[$second_last_line]), 0, 1) == '#')
			{
				$hashtag = 1;
				$hashtag_line = $second_last_line;
			}
			else
			{
				$hashtag = 0;
				$hashtag_line = $last_line;
			}
		}
		
		if (substr(trim($lines[$last_line]), 0, 1) == '#')
		{
			$hashtag = 2;
			$hashtag_line = $last_line;
		}
		
		foreach ($lines as $line)
		{
			if ($line_count == $hashtag_line)
			{
				if ($hashtag == 0)
				{
					$new_file .= $cron_entry."\n";
					$new_file .= '#';
				}
				elseif ($hashtag == 1)
					$new_file .= $cron_entry."\n";
				elseif ($hashtag == 2)
					$new_file .= $cron_entry."\n";
			}
			
			$new_file .= $lines[$line_count]."\n";
			$line_count += 1;
		}
		
		if (file_exists(TEMP_PATH.'/crontab.tmp.php') && is_file(TEMP_PATH.'/crontab.tmp.php'))
			unlink(TEMP_PATH.'/crontab.tmp.php');
		
		if (($file = fopen(TEMP_PATH.'/crontab.tmp.php', 'w+')))
		{
			if (!fwrite($file, $new_file))
				return 4;
		}
		else
			return 3;
		
		if (($stream = ssh2_scp_send($ssh, TEMP_PATH.'/crontab.tmp.php', '/etc/crontab')))
		{
			unlink(TEMP_PATH.'/crontab.tmp.php');
			return 0;
		}
		else
			return 1;
	}
	else
		return 2;
}

function getWeather($location)
{
	if ($location == '00000')
		return 2;
	
	if ((strlen($location) == 4 || strlen($location) == 5) && $location >= 1 && $location <= 99999)
	{
		$country = 'germany';
		
		switch (getConfigValue('config_weather_country'))
		{
			case 'austria': $country = 'austria'; break;
			case 'swiss': $country = 'swiss'; break;
		}
		
		if ($json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$location.','.$country.'&units=metric&lang=de'))
		{
			$obj = json_decode($json);
			
			$data = array();
			$data['city'] = $obj->name; // Stadt
			$data['country'] = $obj->sys->country; // Land
			$data['temp'] = str_replace('.', ',' , round($obj->main->temp)); // Temperatur
			$data['temp_min'] = str_replace('.', ',' , round($obj->main->temp_min)); // Mindest Temperatur
			$data['temp_max'] = str_replace('.', ',' , round($obj->main->temp_max)); // Höchst Temperatur
			$data['humidity'] = $obj->main->humidity; // Luftfeuchtigkeit
			$data['wind'] = str_replace('.', ',' , round($obj->wind->speed)); // Windstärke
			$data['icon'] = $obj->weather[0]->icon; // Wetter Icon
			$data['description'] = $obj->weather[0]->description; // Wetter Beschreibung
			
			if (empty($obj->name))
			{
				$json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$location.','.$country);
				$obj = json_decode($json);
				
				$data['city'] = $obj->name; // Stadt
				$data['country'] = $obj->sys->country; // Land
			}
			
			return $data;
		}
		else
			return 1;
	}
	else
		return 0;
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

function generateUniqId($length = 16)
{
	$random1 = rand(1, 1000);
	$random2 = rand(1, 1000);
	$random3 = rand(1, 1000);
	
	$random11 = 'random'.rand(1, 3);
	$random12 = 'random'.rand(1, 3);
	$random13 = 'random'.rand(1, 3);
	
	$random = md5($$random11 - $$random12 + $$random13);
	$microtime = md5(microtime(true));

	$uniqid = strtoupper(substr(md5($random.$microtime.uniqid()), 0, $length));
	
	return $uniqid;
}
?>