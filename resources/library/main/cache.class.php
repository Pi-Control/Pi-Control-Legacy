<?php
class Cache
{
	const FILE = 0;
	const EXECUTION = 1;
	const EXECUTION_NOCACHE = 2;
	
	private $name, $lifetime, $content, $type, $stream, $loadType = self::FILE, $efFunction, $efArgs, $modificationTime = 0, $statusCode = 0;
	private $cachePath = CACHE_PATH;
	private $fileSuffix = '.cache.php';
	
	public function __construct()
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
		$this->content = $content;
		$this->type = gettype($content);
		
		if ($this->loadType == self::FILE || $this->loadType == self::EXECUTION)
			$this->statusCode = $this->saveToFile();
	}
	
	public function getContent()
	{
		return $this->content;
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
			return 2;
		
		if (flock($this->stream, LOCK_EX) === false)
			return 3;
		
		if (fwrite($this->stream, $this->type, 16) === false)
			return 4;
		
		if (fseek($this->stream, 16) === -1)
			return 5;
		
		if (fwrite($this->stream, $this->encodeType()) === false)
			return 6;
		
		if (flock($this->stream, LOCK_UN) === false)
			return 7;
		
		fclose($this->stream);
		
		if (touch($this->cachePath.$this->name.$this->fileSuffix) === false)
			return 8;
		
		return true;
	}
	
	private function loadFromFile()
	{
		if (!file_exists($this->cachePath.$this->name.$this->fileSuffix) || !is_writable($this->cachePath.$this->name.$this->fileSuffix))
			return 9;
		
		if (($this->stream = fopen($this->cachePath.$this->name.$this->fileSuffix, 'r')) === false)
			return 10;
		
		if (flock($this->stream, LOCK_SH) === false)
			return 11;
		
		if (($type = fread($this->stream, 16)) === false)
			return 12;
		
		$this->type = trim($type);
		
		if (fseek($this->stream, 16) === false)
			return 13;
		
		$data = '';
		
		while (!feof($this->stream))
			$data .= fread($this->stream, 512);
		
		if (flock($this->stream, LOCK_UN) === false)
			return 14;
		
		$this->decodeType($data);
		$this->modificationTime = filemtime($this->cachePath.$this->name.$this->fileSuffix);
		
		fclose($this->stream);
		
		return 0;
	}
	
	private function encodeType()
	{
		switch ($this->type)
		{
			case 'string':
			case 'integer':
			case 'double':
			case 'float':
				return $this->content;
			case 'boolean':
				return settype($this->content, 'string');
			case 'array':
				return json_encode($this->content);
			default:
				return $this->content;
		}
	}
	
	private function decodeType($content)
	{
		switch ($this->type)
		{
			case 'string':
			case 'integer':
			case 'double':
			case 'float':
				$this->content = $content;
				break;
			case 'boolean':
				$this->content = settype($content, 'boolean');
				break;
			case 'array':
				$this->content = json_decode($content, true);
				break;
			default:
				$this->content = $content;
				break;
		}
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