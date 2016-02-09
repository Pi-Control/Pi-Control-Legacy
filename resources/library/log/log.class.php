<?php
if (!defined('PICONTROL')) exit();

class Log
{
	private $stream, $file, $limit;
	private $logPath = CACHE_PATH;
	private $fileSuffix = '.csv';
	
	public function setFile($file, $path = '')
	{
		if (!is_string($file) || $file == '')
			return false;
		
		$this->file = $file;
		
		if ($path != '' && is_string($file))
			$this->logPath = $path;
		
		return true;
	}
	
	public function setLimit($limit = -1)
	{
		if (!is_numeric($limit))
			return false;
		
		$this->limit = $limit;
	}
	
	public function add($line)
	{
		if (!is_array($line))
			return false;
		
		if (!is_resource($this->stream))
			$this->open();
		
		if ($this->log_limit > -1)
			$this->shortLog();
		
		fputcsv($this->stream, $line);
	}
	
	/*public function getAll()
	{		
		if (empty($this->log_file_all))
			$this->log_file_all = file($this->log_file);
		
		return array_map(array($this, 'cube'), $this->log_file_all);
	}
	
	public function getLast()
	{
		if (empty($this->log_file_all))
			$this->log_file_all = file($this->log_file);
		
		return $this->cube($this->log_file_all[count($this->log_file_all)-1]);
	}
	
	private function shortLog()
	{
		if ($this->log_limit == -1)
			return false;
		
		$log_fileL = file($this->log_file);
		
		if (count($log_fileL) >= $this->log_limit)
		{
			$unset_line_count = count($log_fileL) - $this->log_limit;
			
			for ($i = 0; $i <= $unset_line_count; $i++)
			{
				unset($log_fileL[$i]);
			}
			
			$short_fp = fopen($this->log_file, 'w+') or exit('Konnte Log-Datei nicht öffnen: '.$this->log_file);
			fwrite($short_fp, implode('', $log_fileL));
			fclose($short_fp);
		}
	}
	
	public function deleteLog()
	{
		if (is_file($this->log_file))
		{
			if (unlink($this->log_file) or exit('Konnte Log-Datei nicht löschen: '.$this->log_file))
				return true;
		}
		else
			return false;
	}*/
	
	private function open()
	{
		$this->stream = fopen();
	}
	
	public function close()
	{
		fclose($this->stream);
	}
}
?>