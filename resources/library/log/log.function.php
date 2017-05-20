<?php
if (!defined('PICONTROL')) exit();

function getLogs($logPath = '/var/log/', $startPath = null)
{
	$startPath = ($startPath === null) ? $logPath : $startPath;
	
	if (!file_exists($logPath) || !is_readable($logPath))
		return 0;
	
	$logs = [];
	
	foreach (scandir($logPath) as $log)
	{
		if (in_array($log, ['.', '..']))
			continue;
		
		if (is_dir($logPath.$log))
		{
			$logsDirectory = getLogs($logPath.$log.'/', $startPath);
			$logs = array_merge($logs, is_array($logsDirectory) ? $logsDirectory : []);
			continue;
		}
		
		$logName = preg_replace('#(.+?)\.(old|[0-9]+|[0-9]+\.gz)$#i', '$1', $log);
		$key = array_search($logName, array_column($logs, 'name'));
		
		if ($key !== false)
		{
			$logs[$key]['additional'][] = [
				'filename' => $log,
				'readable' => is_readable($logPath.$log),
				'modified' => filemtime($logPath.$log),
				'filesize' => filesize($logPath.$log)
			];
			continue;
		}
		
		$logs[] = [
			'name' => $logName,
			'filename' => $log,
			'path' => $logPath,
			'directory' => preg_replace('#^'.preg_quote($startPath).'#', '', $logPath),
			'readable' => is_readable($logPath.$log),
			'modified' => filemtime($logPath.$log),
			'filesize' => filesize($logPath.$log),
			'additional' => []
		];
	}
	
	return $logs;
}


function getLog($log, $logPath = '/var/log/', $startPath = null)
{
	$logs = getLogs($logPath);
	
	// $split = [total, directory, file]
	$match = preg_match('#^(.*\/)?([^\/]*)$#i', $log, $split);
	
	if ($match !== 1 || count($split) != 3)
		return 0;
	
	$logName = preg_replace('#(.+?)\.(old|[0-9]+|[0-9]+\.gz)$#i', '$1', $split[2]);
	
	if ($split[1] != '')
	{
		foreach ($logs as $log_)
		{
			
		}
		
		$keyDirectory = array_search($split[1], array_column($logs, 'directory'));
		
		if ($keyDirectory === false)
			return 1;
		
		var_dump($keyDirectory);
		
		$logs = $logs[$keyDirectory];
	}
	
	$key = array_search($logName, array_column($logs, 'name'));
	
	var_dump($key, $logName, $logs);
	
	$logs = $logs[$key];
	
	if ($logs['filename'] == $split[2])
	{
		$log = [
			'filename' => $logs['filename'],
			'readable' => $logs['readable'],
			'modified' => $logs['modified'],
			'filesize' => $logs['filesize']
		];
	}
	else
	{
		$key = array_search($split[2], array_column($logs['additional'], 'filename'));
		
		var_dump($logs);
		
		$logs = $logs['additional'][$key];
		
		$log = [
			'filename' => $logs['filename'],
			'readable' => $logs['readable'],
			'modified' => $logs['modified'],
			'filesize' => $logs['filesize']
		];
	}
	
	return [$log, $logs];
}