<?php
if (!defined('PICONTROL')) exit();

class Cron
{
	private $cronFile, $cronPath = CRON_PATH, $interval, $allFiles = array(), $sourceFileFile = '';
	
	public function setName($file = '')
	{
		$this->cronFile = $file;
	}
	
	public function setPath($path = '')
	{
		$this->cronPath = realpath($path).'/';
	}
	
	public function isExists()
	{
		if (empty($this->allFiles))
			$this->allFiles();
		
		if (!empty($this->cronFile) && array_key_exists($this->cronFile, $this->allFiles))
			return true;
		
		return false;
	}
	
	public function getInterval()
	{
		if (empty($this->allFiles))
			$this->allFiles();
		
		if (!empty($this->cronFile) && array_key_exists($this->cronFile, $this->allFiles))
		{
			$this->interval = $this->allFiles[$this->cronFile];
			return $this->interval;
		}
		
		return false;
	}
	
	public function setInterval($interval = 60)
	{
		$this->interval = $interval;
	}
	
	public function save()
	{
		if ($this->sourceFile != '' && $this->interval != '' && $this->cronFile != '')
		{
			if (symlink($this->sourceFile, $this->cronPath.$this->interval.'-'.$this->cronFile.'.php'))
			{
				$this->allFiles = '';
				return true;
			}
		}
		
		return false;
	}
	
	public function setSource($file = '')
	{
		$this->sourceFile = $file;
	}
	
	public function delete()
	{
		if (unlink($this->cronPath.$this->interval.'-'.$this->cronFile.'.php'))
		{
			$this->allFiles = '';
			return true;
		}
		
		return false;
	}
	
	public function getAllFiles()
	{
		if (empty($this->allFiles))
			$this->allFiles();
		
		return $this->allFiles;
	}
	
	private function allFiles()
	{
		if (!function_exists('cube'))
		{
			function cube($n)
			{
				$exp = explode('-', $n);
				$int = $exp[0];
				unset($exp[0]);
				
				return array($int, substr(implode('', $exp), 0, -4));
			}
		}
		
		$folder = $this->cronPath;
		$fileArray = array();
		
		foreach (@scandir($folder) as $file)
		{
			if ($file[0] != '.')
			{
				if (is_file($folder.'/'.$file) && $file != 'init.php')
				{
					$fileArray[] = $file;
				}
			}
		}
		
		foreach(array_map('cube', $fileArray) as $arr_map)
			$this->allFiles[$arr_map[1]] = $arr_map[0];
	}
}
?>