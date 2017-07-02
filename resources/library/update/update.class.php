<?php
if (!defined('PICONTROL')) exit();

class UpdateController
{
	private $updateURL;
	private $updateDownloadURL;
	private $currentVersion;
	private $language;
	private $stage;
	
	private $versions = [
		'release' => [],
		'beta' => []
	];
	
	private $latestVersion = [
		'release' => [],
		'beta' => []
	];
	
	function __construct()
	{
		global $config, $globalLanguage;
		
		$this->updateURL = $config['url']['update'];
		$this->updateDownloadURL = $config['url']['updateDownload'];
		$this->currentVersion = $config['version']['versioncode'];
		$this->language = $globalLanguage;
	}
	
	/**
	 * Hole Daten für Aktualisierung vom Server und
	 * Verarbeite diese.
	 *
	 * @return bool|int
	 */
	public function fetchData()
	{
		if (!class_exists('cURL'))
			(include LIBRARY_PATH.'curl/curl.class.php');
		
		// Hole Daten
		$curl = new cURL($this->updateURL);
		$curl->execute();
		
		if ($curl->getStatusCode() != '200')
			return $curl->getStatusCode();
		
		if ($curl->getResult($data) != JSON_ERROR_NONE)
			return 1;
		
		if (!isset($data['versions'], $data['latest']))
			return 2;
		
		// Versionen aus Daten auslesen und verarbeiten
		foreach ($data['versions'] as $version)
		{
			$updateEntry = new Update();
			$updateEntry->setCode($version['versioncode']);
			$updateEntry->setName($version['version']);
			$updateEntry->setDate($version['date']);
			$updateEntry->setFilesize($version['filesize']);
			$updateEntry->setFilename($version['filename']);
			$updateEntry->setChecksum($version['checksum']);
			$updateEntry->setChangelog(Update::getPreferredLanguage($this->language, $version['changelog']));
			$updateEntry->setStage($version['stage']);
			
			if (in_array($version['stage'], array_keys($this->versions)))
			{
				$this->versions[$version['stage']][] = $updateEntry;
				
				// Wenn letzter Versionscode mit aktuellem Eintrag uebereinstimmt
				if ($data['latest'][$version['stage']] == $version['versioncode'])
					$this->latestVersion[$version['stage']] = $updateEntry;
			}
		}
		
		return true;
	}
	
	/**
	 * Hole letzte stabile Version und gebe diese zurück.
	 *
	 * @return bool|Update
	 */
	public function getLatestRelease()
	{
		if ($this->latestVersion['release'] instanceof Update)
			return $this->latestVersion['release'];
		
		return false;
	}
	
	/**
	 * Hole letzte Beta-Version und gebe diese zurück.
	 *
	 * @return bool|Update
	 */
	
	public function getLatestBeta()
	{
		if ($this->latestVersion['beta'] instanceof Update)
			return $this->latestVersion['beta'];
		
		return false;
	}
	
	/**
	 * Hole letzte Version nach Angabe der Phase.
	 *
	 * @param string $stage Phase
	 * @return bool|Update
	 */
	
	public function getLatestUpdate($stage = NULL)
	{
		if (!is_string($stage))
			$stage = $this->stage;
		
		if ($stage == 'release')
			return $this->getLatestRelease();
		elseif ($stage == 'beta')
			return $this->getLatestBeta();
		
		return false;
	}
	
	/**
	 * Hole nachfolgende, stabile Version nach Versionscode.
	 *
	 * @param int $versioncode Versionscode
	 * @return bool|Update
	 */
	
	public function getNextRelease($versioncode = NULL)
	{
		if (!is_integer($versioncode))
			$versioncode = $this->currentVersion;
		
		foreach ($this->versions['release'] as $version)
		{
			if ($version->getCode() > $versioncode)
				return $version;
		}
		
		return false;
	}
	
	/**
	 * Hole nachfolgende Beta-Version nach Versionscode.
	 *
	 * @param int $versioncode Versionscode
	 * @return bool|Update
	 */
	
	public function getNextBeta($versioncode = NULL)
	{
		if (!is_integer($versioncode))
			$versioncode = $this->currentVersion;
		
		foreach ($this->versions['beta'] as $version)
		{
			if ($version->getCode() > $versioncode)
				return $version;
		}
		
		return false;
	}
	
	/**
	 * Hole nachfolgende Version nach Versionscode und
	 * nach Angabe der Phase.
	 *
	 * @param int $versioncode Versionscode
	 * @param string $stage
	 * @return bool|Update
	 */
	
	public function getNextUpdate($versioncode = NULL, $stage = NULL)
	{
		if (!is_integer($versioncode))
			$versioncode = $this->currentVersion;
		
		if (!is_string($stage))
			$stage = $this->stage;
		
		if ($stage == 'release')
			return $this->getNextRelease($versioncode);
		elseif ($stage == 'beta')
			return $this->getNextBeta($versioncode);
		
		return false;
	}
	
	/**
	 * Prüfe ob neue Version vorhanden ist. Angabe der
	 * Phase ist optional.
	 *
	 * @param string $stage Phase
	 * @return bool
	 */
	
	public function isUpdate($stage = NULL)
	{
		if (!is_string($stage))
			$stage = $this->stage;
		
		if ($stage == 'release')
			return $this->getNextRelease() ? true : false;
		elseif ($stage == 'beta')
			return $this->getNextBeta() ? true : false;
		
		return false;
	}
	
	/**
	 * Hole Version anhand von Versionscode.
	 *
	 * @param int $versioncode Versionscode
	 * @return bool|Update
	 */
	
	public function getUpdate($versioncode)
	{
		if (!is_integer($versioncode))
			return false;
		
		foreach ($this->versions as $stages)
		{
			foreach ($stages as $version)
			{
				if ($version->getCode() == $versioncode)
					return $version;
			}
		}
		
		return false;
	}
	
	/**
	 * Lade angegebene Version herunter und entpacke
	 * Download in Pi Control Verzeichnis.
	 *
	 * @param Update $version Version
	 * @return bool|int
	 */
	
	public function download($version = NULL)
	{
		if (!$version instanceof Update)
			$version = $this->getNextRelease();
		
		if (!class_exists('cURL'))
			(include LIBRARY_PATH.'curl/curl.class.php');
		
		$curl = new cURL($this->updateDownloadURL.'&'.http_build_query(array('file' => $version->getFilename())));
		$curlStatus = $curl->downloadFile(UPDATE_PATH.'update.zip');
		
		if (!is_bool($curlStatus))
		{
			unlink(UPDATE_PATH . 'update.zip');
			return 3 + $curlStatus;
		}
		
		if ($curl->getStatusCode() != '200')
		{
			unlink(UPDATE_PATH.'update.zip');
			return $curl->getStatusCode();
		}
		
		if (md5_file(UPDATE_PATH.'update.zip') != $version->getChecksum())
		{
			unlink(UPDATE_PATH.'update.zip');
			return 1;
		}
		
		if (!class_exists('ZipArchive'))
		{
			unlink(UPDATE_PATH.'update.zip');
			return 2;
		}
		
		$zip = new ZipArchive;
		
		if (($zipError = $zip->open(UPDATE_PATH.'update.zip')) !== true)
			return 3;
		
		$zip->extractTo(PICONTROL_PATH);
		$zip->close();
		
		unlink(UPDATE_PATH.'update.zip');
		
		if (function_exists('apc_clear_cache'))
			apc_clear_cache();
		
		// Verhindere Cachen der init.php
		sleep(3);
		
		if (file_exists(UPDATE_PATH.'setup.php') && is_file(UPDATE_PATH.'setup.php'))
			return 10;
		else
			return true;
	}
	
	/**
	 * Hole Phase.
	 *
	 * @return string
	 */
	
	public function getStage()
	{
		return $this->stage;
	}
	
	/**
	 * Setze Phase. Mögliche Phasen sind "release" und "beta".
	 *
	 * @param string $stage Phase
	 */
	
	public function setStage($stage)
	{
		$this->stage = $stage;
	}
}

class Update
{
	private $code;
	private $name;
	private $date;
	private $filesize;
	private $filename;
	private $checksum;
	private $changelog;
	private $stage;
	
	public function getCode()
	{
		return $this->code;
	}
	
	public function setCode($code)
	{
		$this->code = $code;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function getFilesize()
	{
		return $this->filesize;
	}
	
	public function setFilesize($filesize)
	{
		$this->filesize = $filesize;
	}
	
	public function getFilename()
	{
		return $this->filename;
	}
	
	public function setFilename($filename)
	{
		$this->filename = $filename;
	}
	
	public function getChecksum()
	{
		return $this->checksum;
	}
	
	public function setChecksum($checksum)
	{
		$this->checksum = $checksum;
	}
	
	public function getChangelog()
	{
		return $this->changelog;
	}
	
	public function setChangelog($changelog)
	{
		$this->changelog = $changelog;
	}
	
	public function getStage()
	{
		return $this->stage;
	}
	
	public function setStage($stage)
	{
		$this->stage = $stage;
	}
	
	public function toArray()
	{
		return [
			'code' => $this->code,
			'name' => $this->name,
			'date' => $this->date,
			'filesize' => $this->filesize,
			'filename' => $this->filename,
			'checksum' => $this->checksum,
			'changelog' => $this->changelog,
			'stage' => $this->stage
		];
	}
	
	static function getPreferredLanguage($language, $array)
	{
		if (isset($array[$language]))
			return $array[$language];
		else
			return current($array);
	}

}
?>