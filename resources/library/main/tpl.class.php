<?php

	/**
	 * Beschreibung. Mehr unter: http://manual.phpdoc.org/HTMLSmartyConverter/HandS/phpDocumentor/tutorial_tags.pkg.html
	 */

class FileException extends Exception { }

class PiTpl
{
	// Sprache
	private $tplLanguage = 'de';
	private $tplLanguageFileArray = array();
	
	// Pfade
	private $tplFolderPath = 'public_html/templates/';
	private $tplFileSuffix = '.tpl.php';
	private $tplConfigs = array('main', 'cron');
	private $tplConfigSuffix = '.config.ini.php';
	private $tplLanguagePath = 'resources/languages/';
	
	// Laufzeit
	private $runtimeStart = 0;
	
	// Template Variablen
	private $tplVariables = array();
	
	// Geladene Elemente
	private $tplLoaded = false;
	private $tplLoadedHeader = false;
	private $tplLoadedFooter = false;
	
	// Lade Elemente?
	private $tplLoadHeader = false;
	private $tplLoadFooter = false;
	
	// Fehler / Nachrichten
	private $ifError = false;
	private $tplMsg = array();
	
	private $tpl = NULL;
	private $tplConfigArray = array();
	
	// Headertitel
	private $tplHeaderTitle = '';
	private $tplHeaderTitleFormat = 'Pi Control | %s';
	
	// Footer
	private $tplFooterConfig = array();
	private $tplFooterErrorHandler = array();
	
	// SSH
	private $tplSSH = NULL;
	
	function __construct()
	{
		$this->runtimeStart = microtime(true);
		
		foreach ($this->tplConfigs as $configFile)
		{
			if (file_exists(CONFIG_PATH.$configFile.$this->tplConfigSuffix) === true && is_file(CONFIG_PATH.$configFile.$this->tplConfigSuffix) === true)
				$this->tplConfigArray[$configFile] = parse_ini_file(CONFIG_PATH.$configFile.$this->tplConfigSuffix, true);
		}
	}
	
	/**
	 * Übergibt der Klasse die eigene Klassenvariable.
	 *
	 * <code>$tpl->setTpl($tpl);</code>
	 *
	 * @param class $tpl Klasse
	 * @return bool
	 */
	
	public function setTpl($tpl)
	{
		$this->tpl = $tpl;
		
		return true;
	}
	
	/**
	 * Setzt Sprache.
	 *
	 * <code>$tpl->setLanguage('en'); // Für Englisch.</code>
	 *
	 * @param string $lang Sprache
	 * @return bool
	 */
	
	public function setLanguage($lang = 'de')
	{
		global $globalLanguage;
		
		if (strlen($lang) != 2 || !is_string($lang))
			return false;
		
		$this->tplLanguage = $lang;
		$globalLanguage = $lang;
		
		return true;
	}
	
	/**
	 * Übersetzt Text in andere Sprache.
	 *
	 * <code>$tpl->_t('Hallo %s!', 'Welt'); // Rückgabe: Hallo Welt!</code>
	 *
	 * @param string $text Text
	 * @param string|int|float $args[] Argumente
	 * @return string
	 */
	
	public function _t()
	{
		$args = func_get_args();
		
		if (isset($this->tplLanguageFileArray[$args[0]]) && $this->tplLanguage != 'de')
			$args[0] = $this->tplLanguageFileArray[$args[0]];
		
		return call_user_func_array('sprintf', $args);
	}
	
	/**
	 * Übersetzt Text in andere Sprache und gibt ihn anschließend aus.
	 *
	 * <code>$tpl->_e('Hallo %s!', 'Welt'); // Ausgabe: Hallo Welt!</code>
	 *
	 * @param string $text Text
	 * @param string|int|float $args[] Argumente
	 * @return bool Ausgabe erfolgt mit "echo".
	 */
	
	public function _e()
	{
		$args = func_get_args();
		
		if (isset($this->tplLanguageFileArray[$args[0]]) && $this->tplLanguage != 'de')
			$args[0] = $this->tplLanguageFileArray[$args[0]];
		
		echo call_user_func_array('sprintf', $args);
		
		return true;
	}
	
	/**
	 * Setzt Konfigurationswert für config.ini.php.
	 *
	 * <code>$tpl->setConfig('other.test', 'Wert'); // Weißt der Konfigvariable ['other']['test'] den Wert "Wert" zu.</code>
	 *
	 * @param string $config Konfigschlüssel
	 * @param string $value Konfigwert
	 * @return bool
	 */
	
	public function setConfig($config, $value)
	{
		if (!strlen($config) > 0 || !is_string($config))
			return false;
		
		$file = explode(':', $config);
		
		if (count($file) != 2)
			return false;
		
		$var = explode('.', $file[1]);
		
		if (count($var) != 2)
			return false;
		
		$this->tplConfigArray[$file[0]][$var[0]][$var[1]] = $value;
		
		return writeConfig($this->tplConfigArray[$file[0]], CONFIG_PATH.$file[0].$this->tplConfigSuffix);
	}
	
	/**
	 * Ermittelt Konfigurationswert aus config.ini.php.
	 *
	 * <code>$tpl->getConfig('other.test', 'Wert'); // Ermittelt den Wert von Konfigvariable ['other']['test']. Standardwert: "Wert".</code>
	 *
	 * @param string $config Konfigschlüssel
	 * @param string $default Standardwert
	 * @return string|int Im Fehlerfall der Standardwert, ansonsten den Konfigwert.
	 */
	
	public function getConfig($config, $default = NULL)
	{
		if (!strlen($config) > 0 || !is_string($config))
			return $default;
		
		if (!count($this->tplConfigArray) > 0)
			return $default;
		
		$file = explode(':', $config);
		
		if (count($file) != 2)
			return $default;
		
		$var = explode('.', $file[1]);
		
		if (count($var) != 2)
			return $default;
		
		if (!isset($this->tplConfigArray[$file[0]][$var[0]][$var[1]]))
			return $default;
		
		return $this->tplConfigArray[$file[0]][$var[0]][$var[1]];
	}
	
	/**
	 * Übergibt dem TPL-System eine Variable
	 *
	 * <code>$tpl->assign('test', 'Wert'); // "test" ist der Name der Variable, "Wert" der Wert.</code>
	 *
	 * @param string $name Name der Variable.
	 * @param string $value Wert der Variable.
	 * @return bool
	 */
	
	public function assign($name, $value = NULL)
	{
		if (!strlen($name) > 0 || !is_string($name))
			return false;
		
		$this->tplVariables[$name] = $value;
		
		return true;
	}
	
	/**
	 * Setzt den Ordner mit den Template-Dateien.
	 *
	 * <code>$tpl->setTplFolder('/var/www/public_html/templates'); // Setzt den Pfad auf "/var/www/public_html/templates".</code>
	 *
	 * @param string $path Pfad zum Ordner.
	 * @return bool
	 */
	
	public function setTplFolder($path)
	{
		if (!strlen($path) > 0 || !is_string($path))
			return false;
		
		if (file_exists($path) !== true || is_dir($path) !== true)
			return false;
		
		$this->tplFolderPath = $path;
		
		return true;
	}
	
	/**
	 * Setzt den title-Tag.
	 *
	 * <code>$tpl->setHeaderTitle('Übersicht');</code>
	 *
	 * @param string $title Titel
	 * @return bool
	 */
	
	public function setHeaderTitle($title)
	{
		if (!strlen($title) > 0 || !is_string($title))
			return false;
		
		$this->tplHeaderTitle = $title;
		
		return true;
	}
	
	/**
	 * Setzt das Format für den title-Tag.
	 *
	 * <code>$tpl->setHeaderTitleFormat('Pi Control | %s');</code>
	 *
	 * @param string $format Format für den Titel.
	 * @return bool
	 */
	
	public function setHeaderTitleFormat($format)
	{
		if (!strlen($format) > 0 || !is_string($format))
			return false;
		
		$this->tplHeaderTitleFormat = $format;
		
		return true;
	}
	
	/**
	 * Setzt den Wert, ob der Header angezeigt werden soll oder nicht.
	 *
	 * <code>$tpl->setDrawHeader(true); // Header wird angezeigt.</code>
	 *
	 * @param bool $draw
	 * @return bool
	 */
	
	public function setDrawHeader($draw = true)
	{
		$this->tplLoadHeader = $draw;
		
		return true;
	}
	
	/**
	 * Zeigt Header an.
	 *
	 * <code>$tpl->drawHeader();</code>
	 *
	 * @return bool
	 */
	
	private function drawHeader()
	{
		if ($this->tplLoadHeader !== true)
			return false;
		
		$fileName = CONTENT_PATH.'html_header.php';
		
		$this->tplLoadedHeader = true;
		
		if (file_exists($fileName) !== true || is_file($fileName) !== true)
			throw new FileException(self::_t('Datei "%s" existiert nicht oder ist keine gültige Datei.', $fileName));
		
		$tplMain = $this->tpl;
		
		// Übergebe Titel
		$data['title'] = sprintf($this->tplHeaderTitleFormat, $this->tplHeaderTitle);
		
		(include_once $fileName) or self::tplError(self::_t('Konnte Datei "%s" nicht öffnen und auslesen.', $fileName), __LINE__);
		
		return true;
	}
	
	/**
	 * Setzt den Wert, ob der Footer angezeigt werden soll oder nicht.
	 *
	 * <code>$tpl->setDrawFooter(true, $config, $errorHandler); // Footer wird angezeigt.</code>
	 *
	 * @param bool $draw
	 * @param array $mainConfig Konfig-Array aus main_config.php.
	 * @param array $errorHandler
	 * @return bool
	 */
	
	public function setDrawFooter($draw = true, $mainConfig = NULL, $errorHandler = NULL)
	{
		$this->tplLoadFooter = $draw;
		$this->tplFooterConfig = $mainConfig;
		$this->tplFooterErrorHandler = $errorHandler;
		
		return true;
	}
	
	/**
	 * Zeigt Footer an.
	 *
	 * <code>$tpl->drawFooter();</code>
	 *
	 * @return bool
	 */
	
	private function drawFooter()
	{
		if ($this->tplLoadFooter !== true)
			return false;
		
		$fileName = CONTENT_PATH.'html_footer.php';
		
		$this->tplLoadedFooter = true;
		
		if (file_exists($fileName) !== true || is_file($fileName) !== true)
			throw new FileException(self::_t('Datei "%s" existiert nicht oder ist keine gültige Datei.', $fileName));
		
		$tplMain = $this->tpl;
		$tplConfig = $this->tplFooterConfig;
		$tplErrorHandler = $this->tplFooterErrorHandler;
		
		(include_once $fileName) or self::tplError(self::_t('Konnte Datei "%s" nicht öffnen und auslesen.', $fileName), __LINE__);
		
		return true;
	}
	
	/**
	 * Öffnet Template-Datei und zeigt Inhalt anschließend an.
	 *
	 * <code>$tpl->draw('home'); // Für Home</code>
	 *
	 * @param string $tplFileName Template-Datei
	 * @return bool
	 */
	
	public function draw($tplFileName)
	{
		self::drawHeader();
		
		if ($this->ifError === true)
			return false;
		
		if (!strlen($tplFileName) >= 1 || !is_string($tplFileName))
			return self::tplError(self::_t('Dateiname "%s" ist ungültig.', $tplFileName), __LINE__-1);
		
		if (file_exists($this->tplFolderPath.$tplFileName.$this->tplFileSuffix) !== true || is_file($this->tplFolderPath.$tplFileName.$this->tplFileSuffix) !== true)
			return self::tplError(self::_t('Datei "%s" existiert nicht oder ist keine gültige Datei.', $tplFileName), __LINE__-1);
		
		self::drawMsg();
		
		$data = $this->tplVariables;
		
		(include_once $this->tplFolderPath.$tplFileName.$this->tplFileSuffix) or self::error(self::_t('Konnte Datei "%s" nicht öffnen und auslesen.', $tplFileName), __LINE__);
		
		// Optisch schöner
		echo PHP_EOL;
		
		self::drawFooter();
		
		return true;
	}
	
	/**
	 * Öffnet Error-Template-Datei und zeigt Inhalt + Fehler anschließend an
	 *
	 * <code>$tpl->drawError('Fehler', 'Nachricht', true); // Für Fehler.</code>
	 *
	 * @param string $errorTitle Titel des Fehlers.
	 * @param string $errorMsg Nachricht des Fehlers.
	 * @param bool $errorCancel Soll nur die Fehlermeldung angezeigt werden?
	 * @return bool
	 */
	
	private function drawError($errorTitle, $errorMsg, $errorCancel)
	{
		if (!strlen($errorMsg) > 0 || !is_string($errorMsg))
			return false;
		
		if (file_exists($this->tplFolderPath.'error'.$this->tplFileSuffix) !== true || is_file($this->tplFolderPath.'error'.$this->tplFileSuffix) !== true)
			return false;
		
		if ($errorCancel === true)
			if (self::drawHeader() === false)
				return false;
		
		$data['title'] = $errorTitle;
		$data['msg'] = $errorMsg;
		
		include $this->tplFolderPath.'error'.$this->tplFileSuffix;
		
		if ($errorCancel === true)
			if (self::drawFooter() === false)
				return false;
		
		return true;
	}
	
	/**
	 * Fehler anzeigen.
	 *
	 * <code>$tpl->error('Fehler', 'Nachricht', true); // Für Fehler.</code>
	 *
	 * @param string $errorTitle Titel des Fehlers.
	 * @param string $errorMsg Nachricht des Fehlers.
	 * @param bool $errorCancel Soll nur die Fehlermeldung angezeigt werden?
	 * @return bool
	 *
	 * @TODO Wenn $errorCancel auf false steht, wird der Fehler falsch angezeigt.
	 */
	
	public function error($errorTitle, $errorMsg, $errorCancel = true)
	{
		$this->ifError = $errorCancel;
		
		// Prüfe, ob Error-Template-Datei existiert
		if (self::drawError($errorTitle, $errorMsg, $errorCancel) === true)
			return false;
		
		printf('<h1>%s</h1>%s', $errorTitle, $errorMsg);
		
		return true;
	}
	
	/**
	 * Fehler anzeigen.
	 *
	 * <code>$tpl->tplError('Fehler', __LINE__); // Für Fehler.</code>
	 *
	 * @param string $errorMsg Nachricht des Fehlers.
	 * @param int $errorLine Zeilennummer des Fehlers.
	 * @return bool
	 */
	
	private function tplError($errorMsg, $errorLine = 0)
	{
		self::error('Fehler im TPL-System', sprintf('%s Zeile: %s', $errorMsg, $errorLine), true);
		
		// Rückgabewert für andere Funktion
		return false;
	}
	
	/**
	 * Leitet auf eine andere Seite weiter.
	 *
	 * <code>$tpl->redirect('?s=overview'); // Leitet auf "?s=overview" weiter.</code>
	 *
	 * @param string $url URL auf die weitergeleitet werden soll.
	 * @return bool
	 */
	
	public function redirect($url)
	{
		if (!strlen($url) > 0 || !is_string($url))
			return false;
		
		if (!headers_sent($filename, $linenum))
			exit(header('Location: '.$url));
		else
		{
			self::error(self::_t('Weiterleitung'),
						self::_t('<strong class="red">Header bereits gesendet. Redirect nicht m&ouml;glich, klicke daher stattdessen <a href="setup.php">diesen Link</a> an.</strong>', $url),
						true);
		}
		
		return true;
	}
	
	/**
	 * Zeigt Debugmeldungen an.
	 *
	 * <code>$tpl->showDebug();</code>
	 *
	 * @return bool
	 */
	
	public function showDebug()
	{
		printf(PHP_EOL.'<!-- DEBUG - Start -->'.PHP_EOL.'	<hr /><p>Ladezeit: %f<br />Fehler: %s</p>'.PHP_EOL.'<!-- DEBUG - End -->'.PHP_EOL, round(microtime(true)-$this->runtimeStart, 5), ($this->ifError) ? 'true' : 'false');
		
		return true;
	}
	
	/**
	 * Fügt Infomeldung hinzu.
	 *
	 * <code>$tpl->msg('red', 'Warnung', 'Infotext', true); // Zeigt rote Warnmeldung an.</code>
	 *
	 * @param string $type Type (Farbe) der Meldung. Möglich: red, green, yellow.
	 * @param string $title Titel der Meldung.
	 * @param string $msg Nachricht der Meldung.
	 * @param bool $cancelable Soll die Meldung geschlossen werden können?
	 * @return bool
	 */
	
	public function msg($type, $title = NULL, $msg, $cancelable = true)
	{
		if (!strlen($type) > 0 || !is_string($type) ||
			!strlen($msg) > 0 || !is_string($msg)
		)
			return false;
		
		$this->tplMsg[] = array($type, $title, $msg, $cancelable);
		
		return true;
	}
	
	/**
	 * Zeigt Infomeldung(en) an.
	 *
	 * <code>$tpl->drawMsg();</code>
	 *
	 * @return bool
	 */
	
	private function drawMsg()
	{
		if (is_array($this->tplMsg) !== true || count($this->tplMsg) == 0)
			return false;
		
		if (file_exists($this->tplFolderPath.'msg'.$this->tplFileSuffix) !== true || is_file($this->tplFolderPath.'msg'.$this->tplFileSuffix) !== true)
			return false;
		
		foreach ($this->tplMsg as $key => $msg)
		{
			$data['id']			= $key;
			$data['type']		= $msg[0];
			$data['title']		= $msg[1];
			$data['msg']		= $msg[2];
			$data['cancelable']	= $msg[3];
			
			(include $this->tplFolderPath.'msg'.$this->tplFileSuffix) or self::tplError(self::_t('Konnte Datei "%s" nicht öffnen und auslesen.', $this->tplFolderPath.'msg'.$this->tplFileSuffix), __LINE__);
		}
		
		return false;
	}
	
	/**
	 * Verbindet sich mit SSH.
	 *
	 * <code>$tpl->loadSSH();</code>
	 *
	 * @return bool
	 */
	
	private function loadSSH()
	{
		$ssh = NULL;
		
		(include CONFIG_PATH.'/config_ssh.php') or self::tplError(self::_t('Konnte Datei "%s" nicht öffnen und auslesen.', CONFIG_PATH.'/config_ssh.php'), __LINE__);
		(include CONFIG_PATH.'/config_uniqid.php') or self::tplError(self::_t('Konnte Datei "%s" nicht öffnen und auslesen.', CONFIG_PATH.'/config_uniqid.php'), __LINE__);
		
		if (isset($config_ssh_port, $config_ssh_username, $config_ssh_password) && $config_ssh_port != '' && $config_ssh_username != '' && $config_ssh_password != '')
		{
			$index_ssh_port = $config_ssh_port;
			$index_ssh_username = $config_ssh_username;
			$index_ssh_pasword = $config_ssh_password;
		}
		
		if (isset($config_uniqid) && $config_uniqid != '')
			$index_uniqid = $config_uniqid;
		
		if (isset($index_ssh_port, $index_ssh_username, $index_ssh_pasword))
		{
			$ssh = ssh2_connect('127.0.0.1', $index_ssh_port);
			
			if (isset($index_uniqid) && $index_uniqid !== NULL)
				$ssh_auth = ssh2_auth_password($ssh, $index_ssh_username, trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $index_uniqid, base64_decode($index_ssh_pasword), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
			elseif (isset($_SESSION['ssh']))
				$ssh_auth = ssh2_auth_password($ssh, $index_ssh_username, trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $_SESSION['ssh'], base64_decode($index_ssh_pasword), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
			else
				return false;
		}
		
		if ($ssh === NULL)
			return false;
		
		$this->tplSSH = $ssh;
		
		return true;
	}
	
	/**
	 * Führt einen SSH-Befehl aus.
	 * $canelIfError:	0 = Keine Auswirkung
	 *					1 = Fehlermeldung
	 * 					2 = Fehlermeldung + Abbrechen
	 *
	 * <code>$tpl->executeSSH('ls', true, 0); // Im Fehlerfall wird keine Meldung ausgegeben.</code>
	 *
	 * @param string $command SSH-Befehl
	 * @param bool $blockStream Soll gewartet werden, bis Rückgabe?
	 * @param int $cancelIfError Verhalten im Fehlerfall.
	 * @return bool|array
	 */
	
	public function executeSSH($command, $blockStream = true, $cancelIfError = 1)
	{
		if ($this->tplSSH === NULL)
			if (self::loadSSH() !== true)
				if ($cancelIfError !== 0)
					return self::error(_t('SSH-Zugriffsfehler'), _t('Kein SSH-Zugriff, bitte anmelden! <a href="?s=ssh_login">Jetzt anmelden.</a>'), ($cancelIfError === 1) ? false : true);
		
		if ($this->tplSSH === NULL || ($stream = ssh2_exec($this->tplSSH, $command)) === false)
			return false;
		
		$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
		
		if ($blockStream === true)
		{
			stream_set_blocking($errorStream, true);
			stream_set_blocking($stream, true);
		}
		
		$error = stream_get_contents($errorStream);
		$output = stream_get_contents($stream);
		
		return array($error, $output);
	}
	
	/**
	 * Rückgabe der SSH-Ressource.
	 *
	 * <code>$tpl->getSSHResource();</code>
	 *
	 * @return bool|resource
	 */
	
	public function getSSHResource()
	{
		if ($this->tplSSH === NULL)
			if (self::loadSSH() !== true)
					return false;
		
		return $this->tplSSH;
	}
	
	/**
	 * Ermittelt Informationen der gespeicherten SSH-Informationen.
	 *
	 * <code>$tpl->getSSHInfo();</code>
	 *
	 * @return bool|array
	 */
	
	public function getSSHInfo()
	{
		(include CONFIG_PATH.'/config_ssh.php') or self::tplError(self::_t('Konnte Datei "%s" nicht öffnen und auslesen.', CONFIG_PATH.'/config_ssh.php'), __LINE__);
		(include CONFIG_PATH.'/config_uniqid.php') or self::tplError(self::_t('Konnte Datei "%s" nicht öffnen und auslesen.', CONFIG_PATH.'/config_uniqid.php'), __LINE__);
		
		if (isset($config_ssh_port, $config_ssh_username, $config_ssh_password) && $config_ssh_port != '' && $config_ssh_username != '' && $config_ssh_password != '')
		{
			$index_ssh_port = $config_ssh_port;
			$index_ssh_username = $config_ssh_username;
			$index_ssh_pasword = $config_ssh_password;
		}
		
		if (isset($config_uniqid) && $config_uniqid != '')
			$index_uniqid = $config_uniqid;
		
		if (isset($index_ssh_port, $index_ssh_username, $index_ssh_pasword))
		{
			if (isset($_SESSION['ssh']))
				return array('type' => 'session', 'port' => $index_ssh_port, 'username' => $index_ssh_username);
			elseif (isset($index_uniqid) && $index_uniqid !== NULL)
				return array('type' => 'file', 'port' => $index_ssh_port, 'username' => $index_ssh_username);
			else
				return array('type' => '', 'port' => $index_ssh_port, 'username' => $index_ssh_username);
		}
		
		return false;
	}
	
	/**
	 * Setzt SSH-Informationen.
	 *
	 * <code>$tpl->setSSHInfo(22, 'pi', 'raspberry', false); // Login nur für aktuelle Sitzung</code>
	 *
	 * @param int $port SSH-Port
	 * @param string $username SSH-Benutzername
	 * @param string $password SSH-Passwort
	 * @param bool $saveInFile Langer Login (true) oder nur aktuelle Sitzung (false)?
	 * @return bool
	 */
	
	public function setSSHInfo($port, $username, $password, $saveInFile = false)
	{
		if (!is_array($SSHInfo = self::getSSHInfo()))
			return false;
		
		if ($port != '' && is_numeric($port))
			$SSHInfo['port'] = $port;
		
		if ($username != '' && is_string($username))
			$SSHInfo['username'] = $username;
		
		if ($password != '')
		{
			$uniqid = md5(uniqid(rand(), true));
			$salt_pw = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $uniqid, $password, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
			
			$SSHInfo['password'] = $salt_pw;
			
			if ($saveInFile === false)
				$_SESSION['ssh'] = $uniqid;
			else
			{
				if (($file = fopen(CONFIG_PATH.'/config_uniqid.php', 'w+')) === false)
					return false;
				
				if (fwrite($file, '<?php'."\n".'$config_uniqid = \''.$uniqid.'\';'."\n".'?>') === false)
					return false;
				
				fclose($file);
			}
		}
		
		if (($file = fopen(CONFIG_PATH.'/config_ssh.php', 'w+')) === false)
			return false;
		
		if (fwrite($file, '<?php'."\n".'$config_ssh_port = '.$SSHInfo['port'].';'."\n".
										'$config_ssh_username = \''.$SSHInfo['username'].'\';'."\n".
										'$config_ssh_password = \''.$SSHInfo['password'].'\';'."\n".'?>') === false)
			return false;
		
		fclose($file);
		
		return true;
	}
	
	/**
	 * Löscht SSH-Login.
	 *
	 * <code>$tpl->logoutSSH();</code>
	 *
	 * @return bool
	 */
	
	public function logoutSSH()
	{
		unset($_SESSION['ssh']);
		
		if (($file = fopen('resources/config/config_uniqid.php', 'w+')) === false)
			return false;
		
		if (fwrite($file, '<?php'."\n".'$config_uniqid = NULL;'."\n".'?>') === false)
			return false;
		
		fclose($file);
		
		return true;
	}
}
?>