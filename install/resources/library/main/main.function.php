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
		return false;
	
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
	$langFile = LANGUAGE_PATH.'/'.$lang.'.php';
	
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
	$langFile = LANGUAGE_PATH.'/'.$lang.'.php';
	
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
	global $config;
	
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
		
		return $data['versions'][$currentUpdateKey];
	}
	else
		return 0;
}

function urlIsPublic($url)
{
	$ip = gethostbyname($url);
	$long = ip2long($ip);
	
	if (($long >= 167772160 && $long <= 184549375) || ($long >= -1408237568 && $long <= -1407188993) || ($long >= -1062731776 && $long <= -1062666241) || ($long >= 2130706432 && $long <= 2147483647) || $long == -1)
		return false;
	
	return true;
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

function getTranslatedArrayForJs($translations)
{
	if (!is_array($translations))
		return false;
	
	$output = array();
	
	foreach ($translations as $translation)
		$output[$translation] = _t($translation);
	
	return $output;
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
?>