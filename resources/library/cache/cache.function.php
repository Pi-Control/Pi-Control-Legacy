<?php
if (!defined('PICONTROL')) exit();

function getCacheList()
{
	$fileSuffix = '.cache.php';
	$cacheArray = array('usb_devices' => array(), 'users' => array(), 'weather' => array());
	
	foreach ($cacheArray as $name => $info)
	{
		$cacheArray[$name]['active'] = (getConfig('cache:activation.'.$name, 'false') == 'true') ? true : false;
		$cacheArray[$name]['lifetime'] = getConfig('cache:lifetime.'.$name, 0);
		
		if (file_exists(CACHE_PATH.$name.$fileSuffix) && is_file(CACHE_PATH.$name.$fileSuffix))
		{
			$cacheArray[$name]['filesize'] = filesize(CACHE_PATH.$name.$fileSuffix);
			$cacheArray[$name]['modification'] = filemtime(CACHE_PATH.$name.$fileSuffix);
		}
	}
	
	return $cacheArray;
}

function getCacheName($file)
{
	switch ($file)
	{
		case 'usb_devices':
			return _t('Angeschlossene Ger&auml;te');
		case 'users':
			return _t('Alle Benutzer');
		case 'weather':
			return _t('Wetter');
		default:
			return $file;
	}
}
?>