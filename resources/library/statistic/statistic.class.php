<?php
if (!defined('PICONTROL')) exit();

class LogStatistic
{
	private $file, $stream, $limit = -1, $length = 100;
	
	function __destruct()
	{
		fclose($this->stream);
	}
	
	public function setFile($pFile = '')
	{
		$this->file = $pFile;
	}
	
	public function setLimit($pLimit = -1)
	{
		$this->limit = $pLimit;
	}
	
	public function setLength($pLength = 100)
	{
		$this->$length = $pLength;
	}
	
	public function add($entry, $moreThanOne = false)
	{
		if (!is_array($entry) || empty($entry))
			return false;
		
		if (!is_resource($this->stream))
			$this->open();
		
		if ($this->limit > -1)
			$this->shortLog();
		
		fseek($this->stream, 0, SEEK_END);
		
		if ($moreThanOne === false)
			fputcsv($this->stream, $entry);
		else
		{
			foreach ($entry as $item)
				fputcsv($this->stream, $item);
		}
	}
	
	public function getAll()
	{
		if (!is_resource($this->stream))
			$this->open();
		
		$entries = array();
		
		fseek($this->stream, 0);
		
		while (($entry = fgetcsv($this->stream, $this->length)) !== false)
			$entries[] = $entry;
		
		return $entries;
	}
	
	public function getLast()
	{
		if (!is_resource($this->stream))
			$this->open();
		
		fseek($this->stream, 0);
		
		if (is_array(($data = fgetcsv($this->stream, $this->length))))
			return end($data);
	}
	
	private function shortLog()
	{
		if ($this->limit == -1)
			return false;
		
		if (!is_array(($entries = $this->getAll())))
			return false;
		
		if (count($entries) >= $this->limit)
		{
			$unsetLineCount = count($entries) - $this->limit;
			
			for ($i = 0; $i <= $unsetLineCount; $i++)
				unset($entries[$i]);
			
			fseek($this->stream, 0);
			ftruncate($this->stream, 0);
			
			foreach ($entries as $entry)
				fputcsv($this->stream, $entry);
		}
	}
	
	public function deleteLog()
	{
		if (is_file($this->file))
		{
			if (unlink($this->file) or exit('Konnte Log-Datei nicht löschen: '.$this->file))
				return true;
		}
		else
			return false;
	}
	
	public function clearLog()
	{
		if (!is_resource($this->stream))
			$this->open();
		
		fseek($this->stream, 0);
		ftruncate($this->stream, 0);
	}
	
	public function close()
	{
		fclose($this->stream);
	}
	
	private function open()
	{
		$this->stream = fopen($this->file, 'r+') or exit('Konnte Log-Datei nicht öffnen: '.$this->file);
	}
}
?>