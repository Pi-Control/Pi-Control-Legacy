<?php
function getCacheList($loadInfos = false)
{
	$folder = realpath(CACHE_PATH);
	$fileArray = array('usb_devices' => array(), 'users' => array(), 'weather' => array());
	
	foreach (@scandir($folder) as $file)
		if ($file[0] != '.')
			if (is_file($folder.'/'.$file) && substr($file, -10) == '.cache.php')
			{
				$name = substr($file, 0, -10);
				$fileArray[$name] = array('filesize' => filesize($folder.'/'.$file), 'last_change' => filemtime($folder.'/'.$file));
				
				if ($loadInfos === true)
				{
					$fileArray[$name]['active'] = (getConfig('cache:activation.'.$name, 'false') == 'true') ? true : false;
					$fileArray[$name]['lifetime'] = getConfig('cache:lifetime.'.$name, 0);
				}
			}
	
	return $fileArray;
}

function getCacheName($file)
{
	switch ($file)
	{
		case 'usb_devices':
			return 'Angeschlossene Ger&auml;te';
		case 'users':
			return 'Alle Benutzer';
		case 'weather':
			return 'Wetter';
		default:
			return $file;
	}
}
?>