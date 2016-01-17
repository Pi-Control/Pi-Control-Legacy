<?php
class Cache
{
	const FILE = 0;
	const EXECUTION = 1;
	const EXECUTION_NOCACHE = 2;
	
	private $name, $lifetime, $content, $type, $stream, $loadType = self::FILE, $efFunction, $efArgs, $modificationTime = 0, $statusCode = 0;
	private $cachePath = CACHE_PATH;
	private $fileSuffix = '.cache.php';
	
	public function Cache()
	{
		if (!func_num_args() >= 3)
		{
			$this->statusCode = 1;
			return;
		}
		
		$args = func_get_args();
		$this->name = $args[0];
		$function = $args[1];
		$lifetime = 0;
		unset($args[0], $args[1]);
		$this->efFunction = $function;
		$this->efArgs = $args;
		
		if (($cLifetime = getConfig('cache:activation.'.$this->name, 'false')) == 'false')
		{
			$this->loadType = self::EXECUTION_NOCACHE;
			return;
		}
		
		if (($cLifetime = getConfig('cache:lifetime.'.$this->name, 0)) != 0)
			$lifetime = $cLifetime * 60;
		
		if (file_exists($this->cachePath.$this->name.$this->fileSuffix) && filemtime($this->cachePath.$this->name.$this->fileSuffix) > time() - $lifetime)
			return;
		
		$this->loadType = self::EXECUTION;
	}
	
	private function executeFunction()
	{
		$result = call_user_func_array($this->efFunction, $this->efArgs);
		$this->setContent($result);
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setContent($content)
	{
		$this->content = serialize($content);
		
		if ($this->loadType == self::FILE || $this->loadType == self::EXECUTION)
			$this->statusCode = $this->saveToFile();
	}
	
	public function getContent()
	{
		return unserialize($this->content);
	}
	
	public function getStatusCode()
	{
		return $this->statusCode;
	}
	
	public function load()
	{
		if ($this->loadType == self::FILE)
			$this->statusCode = $this->loadFromFile();
		elseif ($this->loadType == self::EXECUTION || $this->loadType == self::EXECUTION_NOCACHE)
			$this->statusCode = $this->executeFunction();
		
		return false;
	}
	
	private function saveToFile()
	{
		if (($this->stream = fopen($this->cachePath.$this->name.$this->fileSuffix, 'w')) === false)
			return 1;
		
		if (flock($this->stream, LOCK_EX) === false)
			return 2;
		
		if (fwrite($this->stream, $this->content) === false)
			return 3;
		
		if (flock($this->stream, LOCK_UN) === false)
			return 4;
		
		fclose($this->stream);
		
		if (touch($this->cachePath.$this->name.$this->fileSuffix) === false)
			return 5;
		
		return 0;
	}
	
	private function loadFromFile()
	{
		if (!file_exists($this->cachePath.$this->name.$this->fileSuffix) || !is_writable($this->cachePath.$this->name.$this->fileSuffix))
			return 6;
		
		if (($this->stream = fopen($this->cachePath.$this->name.$this->fileSuffix, 'r')) === false)
			return 7;
		
		if (flock($this->stream, LOCK_SH) === false)
			return 8;
		
		$data = '';
		
		while (!feof($this->stream))
			$data .= fread($this->stream, 512);
		
		if (flock($this->stream, LOCK_UN) === false)
			return 9;
		
		fclose($this->stream);
		
		$this->content = $data;
		$this->modificationTime = filemtime($this->cachePath.$this->name.$this->fileSuffix);
		
		return 0;
	}
	
	public function displayHint()
	{
		if ($this->statusCode != 0 || $this->loadType == self::EXECUTION || $this->loadType == self::EXECUTION_NOCACHE)
			return NULL;
		
		return '<div><span class="cached" title="Stand: '.formatTime($this->modificationTime).'"><span>Cached</span><a href="?s=settings&amp;do=cache&amp;clear='.$this->name.'&amp;redirect='.urlencode($_SERVER['QUERY_STRING']).'">Aktualisieren</a></span></div>';
	}
	
	public function clear()
	{
		return unlink($this->cachePath.$this->name.$this->fileSuffix);
	}
}
?>