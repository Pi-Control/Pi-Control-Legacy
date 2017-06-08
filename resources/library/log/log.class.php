<?php
if (!defined('PICONTROL')) exit();

class LogController
{
	
	private $logPath;
	private $logs = [];
	private $sshAvailable = false;
	
	function __construct($path)
	{
		global $tpl;
		
		// Pruefe SSH
		$this->sshAvailable = ($tpl->getSSHResource() instanceof Net_SSH2) ? true : false;
		
		$this->logPath = realpath($path).'/';
		
		if (!file_exists($this->logPath))
			return 1;
		
		if (!is_readable($this->logPath))
			return 2;
		
		$this->scanDirectory($this->logPath);
	}
	
	public function getAll()
	{
		return $this->logs;
	}
	
	public function getAllArray()
	{
		$output = [];
		
		foreach ($this->logs as $log)
			$output[] = $log->toArray();
		
		return $output;
	}
	
	public function getGroupFromRelativePath($path)
	{
		$log = $this->getGroupFromPath($this->logPath.$path);
		
		return $log;
	}
	
	public function getLogFromRelativePath($path)
	{
		if (!($log = $this->getGroupFromPath($this->logPath.$path)))
			return false;
		
		$log = $log->getEntry($this->getFilenameFromPath($path));
		
		return $log;
	}
	
	private function scanDirectory($path)
	{
		$path = realpath($path).'/';
		
		foreach (scandir($path) as $name)
		{
			if (in_array($name, ['.', '..']))
				continue;
			
			if (is_dir($path.$name))
			{
				$this->scanDirectory($path.$name);
				continue;
			}
			elseif (is_file($path.$name))
				$this->addLogFromPath($path.$name);
		}
	}
	
	private function addLogFromPath($path)
	{
		$path = realpath($path);
		
		if (!file_exists($path))
			return 1;
		
		$group = $this->getGroupFromPath($path);
		
		if ($group instanceof LogGroup)
		{
			$filename = $this->getFilenameFromPath($path);
			
			$newEntry = new LogEntry();
			$newEntry->setFilename($filename);
			
			$group->addEntry($newEntry);
		}
		elseif ($group === false)
		{
			$groupName = $this->getGroupNameFromPath($path);
			$groupPath = $this->getGroupPathFromPath($path);
			
			$newGroup = new LogGroup();
			$newGroup->setName($groupName);
			$newGroup->setPath($groupPath);
			$newGroup->setRelativePath(preg_replace('#^'.preg_quote($this->logPath).'#', '', $groupPath));
			$newGroup->setSshAvailable($this->sshAvailable);
			
			$this->logs[] = $newGroup;
			
			// Eintrag hinzufuegen
			$filename = $this->getFilenameFromPath($path);
			
			$newEntry = new LogEntry();
			$newEntry->setFilename($filename);
			
			$newGroup->addEntry($newEntry);
		}
		
		return 1;
	}
	
	private function getFilenameFromPath($path)
	{
		return basename($path);
	}
	
	private function getGroupFromPath($path)
	{
		$path = realpath($path);
		
		$groupName = $this->getGroupNameFromPath($path);
		$groupPath = $this->getGroupPathFromPath($path);
		
		foreach ($this->logs as $log)
		{
			if ($log->name == $groupName && $log->path == $groupPath)
				return $log;
		}
		
		return false;
	}
	
	private function getGroupNameFromPath($path)
	{
		$path = realpath($path);
		
		$p_match = preg_match('#^(.*\/)?([^\/]*)$#i', $path, $matches);
		
		if ($p_match === false || count($matches) != 3)
			return 1;
		
		$p_match = preg_match('#(.+?)(?:\.(?:old|[0-9]+|[0-9]+\.gz))?$#i', $matches[2], $matches);
		
		if ($p_match === false || count($matches) != 2)
			return 2;
		
		return $matches[1];
	}
	
	private function getGroupPathFromPath($path)
	{
		$path = realpath($path);
		
		$p_match = preg_match('#^(.*\/)?([^\/]*)$#i', $path, $matches);
		
		if ($p_match === false || count($matches) != 3)
			return 1;
		
		return $matches[1];
	}
	
	public function setLogPath($path)
	{
		$this->logPath = $path;
	}
	
	public function readLog($path) {
		global $tpl;
		
		if ($this->sshAvailable === true)
		{
			if (substr($path, -3) == '.gz') {
				list ($logOutput, , ) = $tpl->executeSSH('zcat '.escapeshellarg($path));
				list ($logLines, , ) = $tpl->executeSSH('zcat '.escapeshellarg($path).' | wc -l');
			}
			else
			{
				list ($logOutput, , ) = $tpl->executeSSH('cat '.escapeshellarg($path));
				list ($logLines, , ) = $tpl->executeSSH('cat '.escapeshellarg($path).' | wc -l');
			}
		}
		else
		{
			if (substr($path, -3) == '.gz') {
				$logOutput = shell_exec('zcat '.escapeshellarg($path));
				$logLines = shell_exec('zcat '.escapeshellarg($path).' | wc -l');
			}
			else {
				$logOutput = shell_exec('cat '.escapeshellarg($path));
				$logLines = shell_exec('cat '.escapeshellarg($path).' | wc -l');
			}
		}
		
		return array('output' => $logOutput, 'lines' => trim($logLines));
	}
	
	public function isSshAvailable()
	{
		return $this->sshAvailable;
	}
	
}

class LogGroup
{
	
	public $name;
	public $path;
	public $relativePath;
	private $entries = [];
	private $sshAvailable = false;
	
	public function addEntry(LogEntry $entry)
	{
		$entry->setLogGroup($this);
		$this->entries[] = $entry;
	}
	
	public function getEntry($entry)
	{
		if (is_int($entry))
			return $this->entries[$entry];
		elseif (is_string($entry))
		{
			foreach ($this->entries as $entry_)
			{
				if ($entry_->getFilename() == $entry)
					return $entry_;
			}
		}
		
		return false;
	}
	
	public function getMain()
	{
		return $this->entries[0];
	}
	
	public function getAll($withoutMain = false)
	{
		if ($withoutMain === true)
		{
			$dummy = $this->entries;
			unset($dummy[0]);
			return $dummy;
		}
		
		return $this->entries;
	}
	
	public function getCount($withoutMain = false)
	{
		return count($this->getAll($withoutMain));
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setPath($path)
	{
		$this->path = realpath($path).'/';
	}
	
	public function setRelativePath($relativePath)
	{
		$this->relativePath = $relativePath;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getPath()
	{
		return $this->path;
	}
	
	public function getRelativePath()
	{
		return $this->relativePath;
	}
	
	public function isSshAvailable()
	{
		return $this->sshAvailable;
	}
	
	public function setSshAvailable($sshAvailable)
	{
		$this->sshAvailable = $sshAvailable;
	}
	
	public function toArray() {
		$entries = [];
		
		foreach ($this->entries as $entry)
			$entries[] = $entry->toArray();
		
		return [
			'name' => $this->name,
			'path' => $this->path,
			'relativePath' => $this->relativePath,
			'entries' => $entries
		];
	}
}

class LogEntry
{
	
	private $logGroup;
	private $filename;
	
	public function setLogGroup(LogGroup $logGroup)
	{
		$this->logGroup = $logGroup;
	}
	
	public function setFilename($filename)
	{
		$this->filename = $filename;
	}
	
	public function getLogGroup()
	{
		return $this->logGroup;
	}
	
	public function getFilename()
	{
		return $this->filename;
	}
	
	public function getReadable()
	{
		if ($this->logGroup->isSshAvailable())
			return true;
		
		return is_readable($this->getPath());
	}
	
	public function getFilesize()
	{
		return filesize($this->getPath());
	}
	
	public function getModified()
	{
		return filemtime($this->getPath());
	}
	
	public function getPath()
	{
		return $this->logGroup->getPath().$this->filename;
	}
	
	public function getRelativePath()
	{
		return $this->logGroup->getRelativePath().$this->filename;
	}
	
	public function toArray() {
		return [
			'filename' => $this->filename,
			'readable' => $this->getReadable(),
			'filesize' => $this->getFilesize(),
			'modified' => $this->getModified(),
			'path' => $this->getPath(),
			'relativePath' => $this->getRelativePath()
		];
	}
}

?>