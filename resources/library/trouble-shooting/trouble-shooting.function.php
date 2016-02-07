<?php
function getFileFolderStatus(&$item, $key)
{
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
	$siz = filesize($key);
	
	$item['permissionBool'] = ((is_file($key) && $per == 644) || (is_dir($key) && $per == 755)) ? true : false;
	$item['permission'] = $per;
	$item['userGroupBool'] = ($uid['name'] == 'www-data') ? true : false;
	$item['userGroup'] = $uid['name'].':'.$gid['name'];
	$item['filesizeBool'] = ($siz != 0 || substr($key, 0, 14) == 'resources/log/') ? true : false;
	$item['filesize'] = (is_dir($key)) ? getFolderSize($key) : $siz;
    $item['error'] = ($item['permissionBool'] === false || $item['userGroupBool'] === false || $item['filesizeBool'] === false) ? true : false;
}

function filterFilesFolders($item, $key)
{
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
	
	if ($item['error'] == false && isset($compare[$key]) !== true)
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
	$filesFolders = getFilesWithRelativePath(PICONTROL_PATH, true);
	
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
?>