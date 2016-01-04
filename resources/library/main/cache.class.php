<?php
class Cache
{
	private $name, $lifetime, $content, $type, $file, $loaded = 0, $efFunction, $efArgs, $filemtime = 0, $returnCode = true;
	private $cacheFolder = CACHE_PATH;
	private $filePrefix = '.cache.php';
	
	public function __construct()
	{
		if (!func_num_args() >= 3)
			return;
		
		$args = func_get_args();
		
		$name = $args[0];
		$lifetime = 0; //$args[1]
		$function = $args[1];
		
		if (($cLifetime = getConfig('cache:lifetime.'.$name, 0)) != 0)
			$lifetime = $cLifetime * 60;
		
		$this->name = $name;
		
		if (file_exists($this->cacheFolder.$name.$this->filePrefix) && filemtime($this->cacheFolder.$name.$this->filePrefix) > time() - $lifetime)
			return;
		
		unset($args[0], $args[1]); //, $args[2]
		$this->efFunction = $function;
		$this->efArgs = $args;
		$this->loaded = 1;
	}
	
	private function executeFunction()
	{
		$result = call_user_func_array($this->efFunction, $this->efArgs);
		$this->setContent($result);
		$this->loaded = 2;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setContent($content)
	{
		$this->content = $content;
		$this->type = gettype($content);
		
		$this->returnCode = $this->save();
	}
	
	public function getContent()
	{
		if ($this->loaded == 0)
			$this->returnCode = $this->load();
		elseif ($this->loaded == 1)
			$this->executeFunction();
		
		return $this->content;
	}
	
	public function getReturnCode()
	{
		return $this->returnCode;
	}
	
	private function save()
	{
		if (($this->file = fopen($this->cacheFolder.$this->name.$this->filePrefix, 'w')) === false)
			return 0;
		
		if (flock($this->file, LOCK_EX) === false)
			return 1;
		
		if (fwrite($this->file, $this->type, 16) === false)
			return 2;
		
		if (fseek($this->file, 16) === -1)
			return 3;
		
		if (fwrite($this->file, $this->encodeType()) === false)
			return 4;
		
		if (flock($this->file, LOCK_UN) === false)
			return 5;
		
		fclose($this->file);
		
		if (touch($this->cacheFolder.$this->name.$this->filePrefix) === false)
			return 6;
		
		return true;
	}
	
	private function load()
	{
		if (!file_exists($this->cacheFolder.$this->name.$this->filePrefix) || !is_writable($this->cacheFolder.$this->name.$this->filePrefix))
			return 0;
		
		if (($this->file = fopen($this->cacheFolder.$this->name.$this->filePrefix, 'r')) === false)
			return 1;
		
		if (flock($this->file, LOCK_SH) === false)
			return 2;
		
		if (($type = fread($this->file, 16)) === false)
			return 3;
		
		$this->type = trim($type);
		
		if (fseek($this->file, 16) === false)
			return 4;
		
		$data = '';
		
		while (!feof($this->file))
			$data .= fread($this->file, 512);
		
		if (flock($this->file, LOCK_UN) === false)
			return 5;
		
		$this->decodeType($data);
		$this->filemtime = filemtime($this->cacheFolder.$this->name.$this->filePrefix);
		
		fclose($this->file);
		
		return true;
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
		if ($this->loaded != 0)
			return NULL;
		
		return '<div><span class="cached" title="Stand: '.formatTime($this->filemtime).'"><span>Cached</span><a href="?s=settings&amp;do=cache&amp;clear='.$this->name.'&amp;redirect='.urlencode($_SERVER['QUERY_STRING']).'">Aktualisieren</a></span></div>';
	}
	
	public function clear()
	{
		return unlink($this->cacheFolder.$this->name.$this->filePrefix);
	}
}
?>