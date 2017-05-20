<?php
if (!defined('PICONTROL')) exit();

class LogStatistic
{
	private $file, $stream, $limit = -1, $length = 100;
	
	function __destruct()
	{
		if (is_resource($this->stream))
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
		$this->length = $pLength;
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
		
		return true;
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
		
		$entries = $this->getAll();
		
		if (is_array($entries))
			return end($entries);
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
		
		return true;
	}
	
	public function deleteLog()
	{
		if (is_file($this->file))
		{
			if (unlink($this->file) or exit(_t('Konnte Log-Datei nicht l&ouml;schen: %s', $this->file)))
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
		if (!file_exists($this->file) || !is_file($this->file))
			touch($this->file);
		
		$this->stream = fopen($this->file, 'r+') or exit(_t('Konnte Log-Datei nicht &ouml;ffnen: %s', $this->file));
	}
}

class StatisticController
{
	private $statistics = array();
	
	public function __construct($tpl = NULL)
	{
		if (!isset($tpl))
			return;
		
		$jsTranslations = array();
		$jsTranslations[] = 'Es sind noch keine Werte verf&uuml;gbar. Werte werden alle %%s Minuten eingetragen.';
		$jsTranslations[] = 'Es ist ein Fehler aufgetreten! Fehler: %%s';
		$jsTranslations[] = 'Zeit';
		$jsTranslations[] = 'Es ist ein Fehler aufgetreten! Fehlercode: %%s';
		
		$tpl->assign('jsTranslations', $jsTranslations, true);
	}
	
	public function loadStatistics($folder = NULL)
	{
		$files = array();
		
		if ($folder == NULL)
			$possibleFolders = array(LOG_PATH);
		else
			$possibleFolders = array(LOG_PATH.$folder);
			
		while (list($key, $folder) = each($possibleFolders))
		{
			foreach (@scandir($folder) as $file)
			{
				if ($file[0] != '.')
				{
					if (is_file($folder.'/'.$file) && substr($file, -4) == '.csv')
					{
						$fileName = str_replace(LOG_PATH, '', $folder).substr($file, 0, -4);
						$this->statistics[substr(md5($fileName), 0, 8)] = $fileName;
					}
					elseif (is_dir($folder.$file))
						$possibleFolders[] = $folder.$file.'/';
				}
			}
		}
	}
	
	public function getStatisticID($name)
	{
		if (empty($this->statistics))
			$this->loadStatistics();
		
		if (!is_string($name))
			return false;
		
		if (($id = array_search($name, $this->statistics)) !== false)
			return $id;
		
		return false;
	}
	
	public function getStatisticName($id)
	{
		if (empty($this->statistics))
			$this->loadStatistics();
		
		if (!is_string($id))
			return false;
		
		if (isset($this->statistics[$id]))
			return $this->statistics[$id];
		
		return false;
	}
	
	public function checkStatistic($value, $isID = false)
	{
		if (!is_string($value))
			return false;
		
		if (!is_bool($isID))
			return false;
		
		if ($isID === true)
		{
			if ($this->getStatisticName($value) !== false)
				return true;
			else
				return false;
		}
		elseif ($isID === false)
		{
			if ($this->getStatisticID($value) !== false)
				return true;
			else
				return false;
		}
		
		return false;
	}
	
	public function getStatistics()
	{
		if (empty($this->statistics))
			$this->loadStatistics();
		
		return $this->statistics;
	}
}

class StatisticBuilder
{
	private $name, $columns, $title, $prefix, $suffix, $raw, $label, $unit, $cycle, $limits;
	
	public function loadFromFile($name, $plugin = NULL)
	{
		$source = LIBRARY_PATH.'statistic/statistic.config.php';
		
		if ($plugin != NULL && is_string($plugin))
			$source = PLUGINS_PATH.$plugin.'/plugin.statistic.config.php';
		
		if (!file_exists($source) || !is_file($source))
			return false;
		
		include $source;
		
		if (!isset($statisticConfig) || !is_array($statisticConfig))
			return false;
		
		$this->raw = $name;
		
		if (strpos($name, '/') !== false)
		{
			$explodes = explode('/', strrev($name), 2);
			$name = strrev($explodes[0]);
			$prefix = strrev($explodes[1]).'/';
		}
		
		if (!isset($statisticConfig[$name]) && isset($statisticConfig[substr($name, 0, strpos($name, '_'))]))
		{
			if (strpos($name, '_') !== false)
			{
				$explodes = explode('_', strrev($name), 2);
				$suffix = strrev($explodes[0]);
				$name = strrev($explodes[1]);
			}
		}
		
		if (!isset($statisticConfig[$name]))
			return false;
		
		$statistic = $statisticConfig[$name];
		
		$this->name = $name;
		$this->title = _t($statistic['title']);
		$this->prefix = isset($prefix) ? $prefix : '';
		$this->suffix = isset($suffix) ? $suffix : NULL;
		$this->label = _t($statistic['label']);
		$this->unit = $statistic['unit'];
		$this->cycle = $statistic['cycle'];
		$this->limits = $statistic['limits'];
		
		foreach ($statistic['columns'] as $column)
		{
			foreach ($column as &$values)
			{
				if (is_string($values))
					$values = _t($values);
			}
			
			$this->columns[] = $column;
		}
		
		if (substr_count($this->title, '%s') == 1) {
			$this->title = sprintf($this->title, $this->suffix);
		}
		
		if ($this->title == '')
			$this->title = $this->suffix;
		
		return true;
	}
	
	public function setTitle($title)
	{
		if (!is_string($title))
			return false;
		
		$this->title = $title;
	}
	
	public function getId()
	{
		return substr(md5($this->raw), 0, 8);
	}
	
	public function getArray()
	{
		return array(
						'id' => $this->getId(),
						'name' => $this->name,
						'title' => $this->title,
						'prefix' => $this->prefix,
						'suffix' => $this->suffix,
						'raw' => $this->raw,
						'label' => $this->label,
						'unit' => $this->unit,
						'cycle' => $this->cycle,
						'columns' => $this->columns,
						'limits' => $this->limits
					);
	}
	
	public function getJSON()
	{
		$json = json_encode(array(
									'id' => $this->getId(),
									'label' => $this->label,
									'unit' => $this->unit,
									'cycle' => $this->cycle,
									'columns' => $this->columns
								)
							);
		
		return $json;
	}
}
?>