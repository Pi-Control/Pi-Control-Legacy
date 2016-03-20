<?php
if (!defined('PICONTROL')) exit();

function writeToFile($name, $content)
{
	$fileSuffix = '.cache.php';
	
	if (($stream = fopen(CACHE_PATH.$name.$fileSuffix, 'w')) === false)
		return 1;
	
	if (flock($stream, LOCK_EX) === false)
		return 2;
	
	if (fwrite($stream, serialize($content)) === false)
		return 3;
	
	if (flock($stream, LOCK_UN) === false)
		return 4;
	
	fclose($stream);
	
	return 0;
}

function readFromFile($name)
{
	$fileSuffix = '.cache.php';
	
	if (!file_exists(CACHE_PATH.$name.$fileSuffix) || !is_writable(CACHE_PATH.$name.$fileSuffix))
		return 1;
	
	if (($stream = fopen(CACHE_PATH.$name.$fileSuffix, 'r')) === false)
		return 2;
	
	if (flock($stream, LOCK_SH) === false)
		return 3;
	
	$data = '';
	
	while (!feof($stream))
		$data .= fread($stream, 512);
	
	if (flock($stream, LOCK_UN) === false)
		return 4;
	
	fclose($stream);
	
	return unserialize($data);
}

function getFileFolderStatus(&$item, $key)
{
	if (PICONTROL_PATH != $key)
		$key = PICONTROL_PATH.$key;
	
	$item['existsBool'] = $item['exists'] = (file_exists($key) && (is_file($key) || is_dir($key))) ? true : false;
	
	if ($item['existsBool'] === false)
	{
		$item['permissionBool'] = false;
		$item['permission'] = '&#10006;';
		$item['userGroupBool'] = false;
		$item['userGroup'] = '&#10006;';
		$item['filesizeBool'] = false;
		$item['filesize'] = 0;
	    $item['error'] = ($item['permissionBool'] === false || $item['userGroupBool'] === false || $item['filesizeBool'] === false) ? true : false;
		return;
	}
	
	$per = substr(sprintf('%o', fileperms($key)), -3);
	$uid = posix_getpwuid(fileowner($key));
	$gid = posix_getgrgid(filegroup($key));
	$siz = (is_dir($key)) ? getFolderSize($key) : filesize($key);
	
	$item['permissionBool'] = ((is_file($key) && $per == 644) || (is_dir($key) && $per == 755)) ? true : false;
	$item['permission'] = $per;
	$item['userGroupBool'] = ($uid['name'] == 'www-data') ? true : false;
	$item['userGroup'] = $uid['name'].':'.$gid['name'];
	$item['filesizeBool'] = ($siz != 0 || substr($key, strlen(PICONTROL_PATH), 14) == 'resources/log/' || substr($key, strlen(PICONTROL_PATH), 24) == 'install/resources/cache/') ? true : false;
	$item['filesize'] = $siz;
    $item['error'] = ($item['permissionBool'] === false || $item['userGroupBool'] === false || $item['filesizeBool'] === false) ? true : false;
}

function filterFilesFolders($item, $key)
{
	if ($item['error'] == false)
		return false;
	
	return true;
}

function getFilesWithRelativePath($folder, $first = false)
{
	$folderArray = array();
	$fileArray = array();
	$files = array();
	
	$folder = realpath($folder);
	
	foreach (@scandir($folder) as $file)
		if ($file[0] != '.')
			if (is_dir($folder.'/'.$file))
				$folderArray[] = $file;
			else
				$fileArray[] = $file;
	
	if (isset($folderArray))
	{
		foreach ($folderArray as $row)
		{
			$fileReturn = getFilesWithRelativePath($folder.'/'.$row);
			
			$files[str_replace(PICONTROL_PATH, '', $folder.'/'.$row.'/')] = array();
			$files += $fileReturn;
		}
	}
	
	if (isset($fileArray))
	{
		if (substr($folder, 0, 2) == './')
			$folder = substr($folder, 2);
		
		foreach ($fileArray as $row)
		{
			if ($first === true)
				$files[$row] = array();
			else
				$files[str_replace(PICONTROL_PATH, '', $folder.'/'.$row)] = array();
		}
	}
	
	return $files;
}

function fileFolderPermission()
{
	$filesFolders = getFilesWithRelativePath(PICONTROL_PATH.'api/');
	$filesFolders += getFilesWithRelativePath(PICONTROL_PATH.'install/');
	$filesFolders += getFilesWithRelativePath(PICONTROL_PATH.'public_html/');
	$filesFolders += getFilesWithRelativePath(PICONTROL_PATH.'resources/');
	$filesFolders['license.txt'] = array();
	
	$compare = array(
						'index.php' => array(),
						'resources/init.php' => array(),
						'resources/config/cron.config.ini.php' => array(),
						'resources/config/login.config.ini.php' => array(),
						'resources/config/main.config.ini.php' => array(),
						'resources/config/user.config.ini.php' => array(),
						'resources/cron/init.php' => array(),
						'resources/cron/' => array(),
						'resources/log/' => array(),
						'resources/plugins/' => array(),
						PICONTROL_PATH => array()
					);
	
	$filesFolders += $compare;
	array_walk($filesFolders, 'getFileFolderStatus');
	$filesFolders = array_filter($filesFolders, 'filterFilesFolders', ARRAY_FILTER_USE_BOTH);
	ksort($filesFolders);
	
	return $filesFolders;
}

function setGlobalLanguage()
{
	global $globalLanguage;
	
	$language = 'de';
	$languageBuffer = $language;
	
	$languageBuffer = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	
	$dataFile = json_decode(readFromFile('language'), true);
	if (isset($dataFile['language']) && $dataFile['language'] != '')
		$languageBuffer = $dataFile['language'];
	
	if (isset($_COOKIE['_pi-control_install_language']) && $_COOKIE['_pi-control_install_language'] != '')
		$languageBuffer = $_COOKIE['_pi-control_install_language'];
	
	if (isset($_GET['lang']) && $_GET['lang'] != '')
		$languageBuffer = $_GET['lang'];
	
	switch ($languageBuffer)
	{
	    case 'en':
	    $language = 'en';
	        break;
	}
	
	$globalLanguage = $language;
}

function _t2()
{
	global $globalLanguageArray;
	
	$args = func_get_args();
	$output = array();
	
	foreach (array_pop($args) as $lang)
	{
		$langFile = LANGUAGE_PATH.'/'.$lang.'.php';
		
		if (empty($globalLanguageArray) && file_exists($langFile) === true && is_file($langFile) === true)
		{
			include $langFile;
			$globalLanguageArray = $langArray;
		}
		
		$checksum = substr(md5($args[0]), 0, 8);
		if (isset($globalLanguageArray[$checksum]) && $lang != 'de')
			$args[0] = $globalLanguageArray[$checksum];
		
		$output[$lang] = $args[0];
	}
	
	return $output;
}

function setCronToCrontab($type, $port, $username, $password, $privateKey)
{
	set_include_path(LIBRARY_PATH.'terminal');
	
	if (!class_exists('Net_SSH2'))
	{
		include(LIBRARY_PATH.'terminal/Net/SSH2.php');
		include(LIBRARY_PATH.'terminal/File/ANSI.php');
		include(LIBRARY_PATH.'terminal/Crypt/RSA.php');
	}
	
	$ssh = NULL;
	
	$ssh = new Net_SSH2('127.0.0.1', $port);
	
	if ($type == 'password')
	{
		if (!$ssh->login($username, $password))
			return false;
	}
	
	if ($type == 'publickey')
	{
		$sshPrivateKey = new Crypt_RSA();
		
		if ($password != '')
			$sshPrivateKey->setPassword($password);
		
		$sshPrivateKey->loadKey($privateKey);
		
		if (!$ssh->login($username, $sshPrivateKey))
			return false;
	}
	
	$cronEntry = '* * * * * www-data php -f "'.CRON_PATH.'init.php" >/dev/null 2>&1 # By Pi Control';
	exec('cat /etc/crontab', $crontab);
	$cronMatch = preg_match('/^\*\s\*\s\*\s\*\s\*\swww\-data\sphp \-f "'.preg_quote(CRON_PATH, '/').'init\.php"(.*)/im', implode(PHP_EOL, $crontab));
	
	if ($cronMatch === 0)
	{
		if ($crontab[count($crontab)-2] == '#')
			$crontab = array_merge(array_slice($crontab, 0, -2), array($cronEntry), array_slice($crontab, -2));
		elseif ($crontab[count($crontab)-1] == '#')
			$crontab = array_merge(array_slice($crontab, 0, -1), array($cronEntry), array_slice($crontab, -1));
		else
			$crontab = array_merge($crontab, array($cronEntry, '#'));
	}
	elseif ($cronMatch === 1)
		return true;
	
	$status = $ssh->exec('echo -e '.escapeshellarg(implode('\n', $crontab)).' | sudo /bin/su -c "cat > /etc/crontab"');
	
	if ($status == '')
		return true;
	
	return false;
}
?>