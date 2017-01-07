<?php
(include_once realpath(dirname(__FILE__)).'/../main_config.php') or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');

(include_once LIBRARY_PATH.'/main/error_codes.php')				or die($error_code['0x0001']);
(include_once LIBRARY_PATH.'/main/rain.tpl.nocache.class.php')	or die($error_code['0x0002']);
(include_once LIBRARY_PATH.'/main/functions.php')				or die($error_code['0x0003']);

raintpl::$tpl_dir = TEMPLATES_PATH.'/';

$tpl = new RainTPL;
$tpl->assign('html_path_prefix', '../../');

function errorMsg($msg)
{
	if ($msg != '')
	{
		return '<div class="box info_red">
	<div class="inner">
		<strong>'.$msg.'</strong>
	</div>
</div>
';
	}
	
	return '';
}

function setConfig($config, $value, $customFile = NULL)
{
	$configPath = '../../picontrol2-0/resources/config/';
	$configFileSuffix = '.config.ini.php';
	
	if ($customFile !== NULL)
		$configPath = $customFile;
	
	$file = explode(':', $config);
	
	if (count($file) != 2)
		return false;
	
	$configFile = $configPath.$file[0].$configFileSuffix;
	
	if (file_exists($configFile) !== true || is_file($configFile) !== true)
	{
		if (!touch($configFile))
			return false;
	}
	
	$configArray = parse_ini_file($configFile, true);
	
	if (!strlen($config) > 0 || !is_string($config))
		return false;
	
	$var = explode('.', $file[1]);
	
	if (count($var) != 2)
		$configArray[$var[0]] = $value;
	else
		$configArray[$var[0]][$var[1]] = $value;
	
	return writeConfig($configArray, $configFile);
}

function writeConfig($configArray, $configFile)
{
	$res = array(';<?php', ';die();');
	
	ksort($configArray);
	
	foreach ($configArray as $key => $val)
	{
		if (is_array($val))
		{
			$res[] = PHP_EOL."[$key]";
			
			foreach ($val as $skey => $sval)
				$res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
		}
		else
			$res[] = PHP_EOL."$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
	}
	
	$res[] = ';?>';
	
	if (file_exists($configFile) !== true || is_file($configFile) !== true || is_writeable($configFile) !== true)
		return false;
	
	if ($fp = fopen($configFile, 'w'))
	{
		$startTime = microtime();
		do
		{
			$canWrite = flock($fp, LOCK_EX);
			
			// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			if (!$canWrite)
				usleep(round(rand(0, 100)*1000));
		} while ((!$canWrite) && ((microtime()-$startTime) < 1000));
		
		// file was locked so now we can store information
		if ($canWrite)
		{
			fwrite($fp, implode(PHP_EOL, $res));
			flock($fp, LOCK_UN);
		}
		fclose($fp);
	}
	
	return true;
}

class Cron
{
	private $cronFile, $cronPath = '', $interval, $allFiles = array(), $sourceFileFile = '';
	
	public function setName($file = '')
	{
		if (defined('PLUGIN_ID'))
			$file = 'plugin.'.PLUGIN_ID.'.'.$file;
		
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

defined('HTTP_GET')		or define('HTTP_GET', 'GET');
defined('HTTP_POST')	or define('HTTP_POST', 'POST');
defined('HTTP_PUT')		or define('HTTP_PUT', 'PUT');
defined('HTTP_DELETE')	or define('HTTP_DELETE', 'DELETE');

class cURL
{
	private $handler, $result, $info, $method = HTTP_GET, $url, $parameter = array(), $parameterRaw = '', $header = array(), $statusCode = 0, $customStatusCode = 0;
	
	public function cURL($url, $method = HTTP_GET, $parameter = NULL)
	{
		$this->setUrl($url);
		$this->setMethod($method);
		$this->addParameter($parameter);
	}
	
	public function execute()
	{
		$this->handler = curl_init();
		curl_setopt($this->handler, CURLOPT_URL, $this->url);
		curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, $this->method);
		
		if ($this->method != HTTP_GET && !empty($this->parameter))
		{
			curl_setopt($this->handler, CURLOPT_POST, count($this->parameter));
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->parameter));
		}
		
		if ($this->method != HTTP_GET && !empty($this->parameterRaw))
		{
			curl_setopt($this->handler, CURLOPT_POST, 1);
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->parameterRaw);
		}
		
		if (!empty($this->header))
			curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->header);
		
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		
		if (($data = curl_exec($this->handler)) === false)
		{
			if (curl_errno($this->handler))
				$this->customStatusCode = curl_errno($this->handler);
			
			$this->info = curl_getinfo($this->handler);
			curl_close($this->handler);
			return false;
		}
		
		$this->info = curl_getinfo($this->handler);
		curl_close($this->handler);
		
		$this->result = $data;
	}
	
	public function setUrl($url)
	{
		if (($url = trim($url)) == '')
			return $this->setCustomStatusCode(100);
		
		$this->url = $url;
	}
	
	public function addParameter($parameter)
	{
		if (!is_array($parameter))
			return false;
		
		$this->parameter += $parameter;
	}
	
	public function setParameterRaw($parameter)
	{
		$this->parameterRaw = $parameter;
	}
	
	public function addHeader($header)
	{
		if (!is_array($header))
			return false;
		
		$this->header += $header;
	}
	
	public function setMethod($method)
	{
		if (!in_array($method, array(HTTP_GET, HTTP_POST, HTTP_PUT, HTTP_DELETE)))
			return false;
		
		$this->method = $method;
	}
	
	public function getResult(&$result = -1, $jsonFormat = true)
	{
		if ($result === -1)
		{
			if ($jsonFormat === true)
				return json_decode($this->result, true);
			else
				return $this->result;
		}
		else
		{
			if ($jsonFormat === true)
			{
				$result = json_decode($this->result, true);
				return json_last_error();
			}
			else
				$result = $this->result;
		}
	}
	
	public function getInfo($name)
	{
		return $this->info[$name];
	}
	
	public function getInfos()
	{
		return $this->info;
	}
	
	public function getStatusCode()
	{
		return $this->info['http_code'];
	}
	
	private function setCustomStatusCode($code = -1)
	{
		$this->customStatusCode = $code;
		return;
	}
}

function getOnlinePlugins()
{
	if (!class_exists('cURL'))
		(include LIBRARY_PATH.'curl/curl.class.php');
	
	$curl = new cURL('https://pi-control.de/service/v1/plugin/');
	$curl->execute();
	
	if ($curl->getStatusCode() != '200')
		return $curl->getStatusCode();
	
	if ($curl->getResult($data) != JSON_ERROR_NONE)
		return 0;
	
	if (!isset($data['plugins']))
		return 1;
	
	array_multisort($data['plugins'], SORT_ASC);
	
	return $data['plugins'];
}

function deleteFolder($direction)
{
	$files = array_diff(scandir($direction), array('.','..'));
	
	foreach ($files as $file)
		(is_dir($direction.'/'.$file)) ? deleteFolder($direction.'/'.$file) : unlink($direction.'/'.$file);
	
	return rmdir($direction);
}

$userCreated = (file_exists('../../picontrol2-0/resources/config/user.config.ini.php') && is_file('../../picontrol2-0/resources/config/user.config.ini.php') && filesize('../../picontrol2-0/resources/config/user.config.ini.php') > 80) ? true : false;
$errorMsg = '';

if (!(version_compare(PHP_VERSION, '5.5.0') >= 0))
	$errorMsg = 'Deine PHP-Version ('.PHP_VERSION.') ist veraltet und nicht mit Pi Control 2.0 kompatibel. Bitte aktualisiere mindestens auf PHP 5.5!';

if ($userCreated == true && !isset($_GET['step']))
{
	header('Location: setup.php?step=2');
	exit();
}

if (isset($_POST['submit']) && $_POST['submit'] != '')
{
	if (isset($_POST['username'], $_POST['password'], $_POST['password2']) && ($pUsername = trim($_POST['username'])) != '' && ($pPassword = $_POST['password']) != '' && ($pPassword2 = $_POST['password2']) != '')
	{
		if (preg_match('/^[a-z][a-z0-9\-_]{1,31}$/i', $pUsername) === 1)
		{
			$lowerUsername = strtolower($pUsername);
			if (preg_match('/^[a-z0-9_\-\+\*\/\#\.]{4,64}$/i', $pPassword) === 1)
			{
				if ($pPassword == $pPassword2)
				{
					setConfig('user:user_'.$lowerUsername.'.username', $pUsername);
					setConfig('user:user_'.$lowerUsername.'.created', time());
					setConfig('user:user_'.$lowerUsername.'.password', password_hash($pPassword, PASSWORD_BCRYPT));
					setConfig('user:user_'.$lowerUsername.'.last_login', 0);
					
					$userCreated = true;
				}
				else
					$errorMsg = 'Die angegebenen Passw&ouml;rter stimmen nicht &uuml;berein!';
			}
			else
				$errorMsg = 'Leider ist das Passwort ung&uuml;ltig! Das Passwort muss aus 4 bis 64 Zeichen bestehen und darf nur folgende Zeichen beinhalten: A-Z 0-9 - _ + * / # .';
		}
		else
			$errorMsg = 'Leider ist der Benutzername ung&uuml;ltig! Der Benutzername muss aus 2 bis 32 Zeichen bestehen. Das erste Zeichen muss ein Buchstabe sein und es sind nur folgende Zeichen erlaubt: A-Z 0-9 - _';
	}
	else
		$errorMsg = 'Bitte alle Felder ausf&uuml;llen!';
}

if (!$userCreated || !empty($errorMsg))
{
	$tpl->assign('content', errorMsg($errorMsg).'
		<div class="box">
				<div class="inner-header">
					<span>Aktualisierung auf Pi Control 2.0 - Schritt 1</span>
				</div>
				<div class="inner">
					Seit Pi Control 2.0 ist es n&ouml;tig einen Benutzer f&uuml;r Pi Control zu erstellen. Dieser Benutzer hat nichts mit dem SSH-Login zu tun und wird nur zur Anmeldung an Pi Control genutzt.
				</div>
		</div>
		<div class="box">
				<div class="inner-header">
					<span>Benutzer erstellen</span>
				</div>
				<form action="setup.php?step=2" method="post">
					<div class="inner-bottom">
						<table class="table_simple">
							<tr>
								<td style="width: 30%;"><strong>Benutzername</strong></td>
								<td><input type="text" name="username" maxlength="32" /></td>
							</tr>
							<tr>
								<td><strong>Passwort</strong></td>
								<td><input type="password" name="password" maxlength="64" /></td>
							</tr>
							<tr>
								<td><strong>Passwort wiederholen</strong></td>
								<td><input type="password" name="password2" maxlength="64" /></td>
							</tr>
						</table>
					</div>
					<div class="inner">
						<input type="submit" name="submit" value="Benutzer erstellen" '.(!(version_compare(PHP_VERSION, '5.5.0') >= 0) ? 'disabled="disabled"' : '').' />
					</div>
				</form>
			</div>
		</div>');
}
else
{
	if (isset($_GET['step']) && $_GET['step'] == '2')
	{
		$tpl->assign('content', errorMsg($errorMsg).'
			<div class="box">
					<div class="inner-header">
						<span>Aktualisierung auf Pi Control 2.0 - Schritt 2</span>
					</div>
					<div class="inner-bottom">
						Im n&auml;chsten Schritt der Aktualisierung werden alle Einstellungen, Statistiken und Plugins des alten Pi Control &uuml;bernommen und geladen. Dieser Vorgang kann einen Augenblick dauern.
					</div>
					<div class="inner">
						<a href="setup.php?step=3"><button>Daten umziehen</button></a>
					</div>
				</div>
			</div>');
	}
	elseif (isset($_GET['step']) && $_GET['step'] == '3')
	{
		do
		{
			// Einstellungen uebernehmen
			include CONFIG_PATH.'/config.php';
			
			if (isset($config_access_public))		setConfig('main:access.external', ($config_access_public) ? 'true' : 'false');
			
			if (file_exists(CRON_PATH.'/1-coretemp_monitoring.php') && is_file(CRON_PATH.'/1-coretemp_monitoring.php'))
			{
				$cron = new Cron;
				$cron->setName('coretemp_monitoring');
				$cron->setInterval(1);
				$cron->setSource('../../picontrol2-0/resources/templates/coretemp_monitoring.tmp.php');
				$cron->save();
			}
			
			if (isset($config_temp_celsius))		setConfig('main:monitoringCpuTemp.maximum', $config_temp_celsius);
			
			if (isset($config_temp_mail))			setConfig('main:monitoringCpuTemp.email', $config_temp_mail);
			if (isset($config_temp_mail))			setConfig('main:monitoringCpuTemp.emailEnabled', ($config_temp_mail != '') ? 'true' : 'false');
			if (isset($config_temp_mail_id))		setConfig('main:monitoringCpuTemp.id', ($config_temp_mail_id != '') ? $config_temp_mail_id.'00000000' : '');
			if (isset($config_temp_mail_code))		setConfig('main:monitoringCpuTemp.code', ($config_temp_mail_code != '') ? $config_temp_mail_code.'000000' : '');
			
			if (isset($config_temp_command))		setConfig('main:monitoringCpuTemp.shell', $config_temp_command);
			if (isset($config_temp_command))		setConfig('main:monitoringCpuTemp.shellEnabled', ($config_temp_command != '') ? 'true' : 'false');
			
			if (isset($config_overview_reload_time))		setConfig('main:overview.interval', $config_overview_reload_time);
			if (isset($config_overview_connected_devices))	setConfig('main:overview.showDevices', ($config_overview_connected_devices) ? 'true' : 'false');
			
			if (isset($config_last_cron_execution))	setConfig('cron:execution.cron', $config_last_cron_execution);
			
			if (isset($config_overview_weather))	setConfig('main:weather.activation', ($config_overview_weather) ? 'true' : 'false');
			if (isset($config_overview_weather))	setConfig('main:weather.service', 'yahoo');
			if (isset($config_weather_type))		setConfig('main:weather.type', $config_weather_type);
			if (isset($config_weather_country))		setConfig('main:weather.country', $config_weather_country);
			if (isset($config_weather_postcode))	setConfig('main:weather.postcode', $config_weather_postcode);
			if (isset($config_weather_city))		setConfig('main:weather.city', $config_weather_city);
			
			if (isset($config_statistic_hide))
			{
				$statistic = array_filter(explode('~', $config_statistic_hide));
				
				foreach ($statistic as $key => &$value)
					$value = substr(md5('statistic/'.$value), 0, 8);
				
				setConfig('main:statistic.hidden', htmlspecialchars(serialize($statistic)));
			}
			
			if (isset($config_network_count))
			{
				$network = json_decode($config_network_count, true);
				
				setConfig('main:network.overflowCount', htmlspecialchars(serialize($network)));
			}
			
			if (file_exists(CRON_PATH.'/1-notification.php') && is_file(CRON_PATH.'/1-notification.php'))
			{
				$cron = new Cron;
				$cron->setName('notification');
				$cron->setInterval(1);
				$cron->setSource('../../picontrol2-0/resources/templates/notification.tmp.php');
				$cron->save();
			}
			
			if (isset($config_notification))				setConfig('main:notificationPB.enabled', ($config_notification) ? 'true' : 'false');
			if (isset($config_notification_token))			setConfig('main:notificationPB.token', $config_notification_token);
			if (isset($config_notification_picontrol))		setConfig('main:notificationPB.picontrolVersionEnabled', ($config_notification_picontrol) ? 'true' : 'false');
			if (isset($config_notification_cpu_temp))		setConfig('main:notificationPB.cpuTemperatureEnabled', ($config_notification_cpu_temp) ? 'true' : 'false');
			if (isset($config_notification_cpu_temp_value))	setConfig('main:notificationPB.cpuTemperatureMaximum', $config_notification_cpu_temp_value);
			if (isset($config_notification_memory))			setConfig('main:notificationPB.memoryUsedEnabled', ($config_notification_memory) ? 'true' : 'false');
			if (isset($config_notification_memory_value))	setConfig('main:notificationPB.memoryUsedLimit', $config_notification_memory_value);
			
			// Statistiken uebernehmen
			$folder = LOG_PATH;
			
			foreach (@scandir($folder) as $file)
			{
				if ($file[0] != '.')
				{
					if (is_file($folder.'/'.$file) && substr($file, -8) == '.log.txt')
					{
						$fLogOld = file($folder.'/'.$file);
						
						$fLogNew = fopen('../../picontrol2-0/resources/log/statistic/'.substr($file, 0, -8).'.csv', 'w+');
						fwrite($fLogNew, str_replace('~', ',', implode('', $fLogOld)));
						fclose($fLogNew);
					}
				}
			}
			
			// Plugins herunterladen
			$onlinePlugins = getOnlinePlugins();
			$folder = PLUGINS_PATH;
			$newPluginsPath = '../../picontrol2-0/resources/plugins/';
			$pluginErrorMsg = array();
			
			if (is_array($onlinePlugins))
			{
				foreach (@scandir($folder) as $file)
				{
					if ($file[0] != '.')
					{
						if (is_dir($folder.'/'.$file))
						{
							$pluginId = $file;
							
							if ($pluginId == 'temp')
								$pluginId = 'temperature';
							
							if (isset($onlinePlugins[$pluginId]))
							{
								if (($data = file_get_contents('https://pi-control.de/?service=plugin&'.http_build_query(array('file' => $onlinePlugins[$pluginId]['versions'][$onlinePlugins[$pluginId]['latestVersion']]['download']['filename'])))) !== false)
								{
									if (file_put_contents($newPluginsPath.'plugin.zip', $data, LOCK_EX) !== false)
									{
										if (md5_file($newPluginsPath.'plugin.zip') == $onlinePlugins[$pluginId]['versions'][$onlinePlugins[$pluginId]['latestVersion']]['download']['checksum'])
										{
											if (mkdir($newPluginsPath.$pluginId.'/'))
											{
												$zip = new ZipArchive;
												
												if (($zipError = $zip->open($newPluginsPath.'plugin.zip')) === true)
												{
													$zip->extractTo($newPluginsPath.$pluginId.'/');
													$zip->close();
													unlink($newPluginsPath.'plugin.zip');
												}
												else
													$pluginErrorMsg[$pluginId] = 'Leider ist ein Fehler beim entpacken des Plugins aufgetreten! Fehlercode: '.$zipRrror;
											}
											else
												$pluginErrorMsg[$pluginId] = 'Der Ordner f&uuml;r das Plugin konnte nicht erstellt werden. Ordnerberechtigungen pr&uuml;fen und erneut versuchen. Sollte der Fehler weiterhin auftreten, schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiterhelfen kann.';
										}
										else
											$pluginErrorMsg[$pluginId] = 'Das Plugin wurde nicht vollst&auml;ndig heruntergeladen. Bitte versuche es erneut. Sollte der Fehler weiterhin auftreten, schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiterhelfen kann.';
									}
									else
										$pluginErrorMsg[$pluginId] = 'Konnte das Plugin nicht zwischenspeichern! Bitte schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';
								}
								else
									$pluginErrorMsg[$pluginId] = 'Konnte das Plugin auf dem Server nicht finden! Bitte schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>, sodass ich dir m&ouml;glichst schnell weiter helfen kann.';
							}
						}
					}
				}
			}
			
			if (!empty($pluginErrorMsg))
			{
				$errorMsg = 'Es ist/sind ein/mehrere Fehler beim Herunterladen des/der Plugins aufgetreten:<br /><br />';
				
				foreach ($pluginErrorMsg as $plugin => $msg)
					$errorMsg .= '<u>['.$plugin.']</u> '.$msg.'<br />';
				
				$tpl->assign('content', errorMsg($errorMsg).'
					<div class="box">
							<div class="inner-header">
								<span>Aktualisierung auf Pi Control 2.0 - Schritt 2</span>
							</div>
							<div class="inner">
								<a href="setup.php?step=3"><button>Vorgang wiederholen</button></a>
							</div>
						</div>
					</div>');
				
				break;
			}
			
			foreach (@scandir($folder) as $file)
			{
				if ($file[0] != '.')
				{
					if (is_dir($folder.'/'.$file))
					{
						$pluginId = $file;
						
						if ($pluginId == 'temp')
							$pluginId = 'temperature';
						
						if (file_exists($newPluginsPath.'/'.$pluginId) && is_dir($newPluginsPath.'/'.$pluginId))
						{
							switch ($pluginId)
							{
								case 'fritzbox':
									include PLUGINS_PATH.'/fritzbox/config/config.php';
									
									if (isset($config_plugin_fritzbox_os)) setConfig('plugin.fritzbox.main:version', $config_plugin_fritzbox_os);
									if (isset($config_plugin_fritzbox_address)) setConfig('plugin.fritzbox.main:address', $config_plugin_fritzbox_address);
									
									break;
								case 'lg_remote_control':
									include PLUGINS_PATH.'/lg_remote_control/config/config.php';
									
									if (isset($config_plugin_lg_remote_control_address)) setConfig('plugin.lg_remote_control.main:address', $config_plugin_lg_remote_control_address);
									if (isset($config_plugin_lg_remote_control_port)) setConfig('plugin.lg_remote_control.main:port', $config_plugin_lg_remote_control_port);
									if (isset($config_plugin_lg_remote_control_key)) setConfig('plugin.lg_remote_control.main:key', $config_plugin_lg_remote_control_key);
									
									break;
								case 'motion':
									include PLUGINS_PATH.'/motion/config/config.php';
									
									if (isset($config_plugin_webcam_port) && $config_plugin_webcam_port != '')
										setConfig('plugin.motion.main:cameras', htmlspecialchars(serialize(array(array('enabled' => true, 'port' => $config_plugin_webcam_port)))));
									
									break;
								case 'temperature':
									if (file_exists(CRON_PATH.'/5-plugin.temp.php') && is_file(CRON_PATH.'/5-plugin.temp.php'))
									{
										$cron = new Cron;
										$cron->setName('temperature');
										$cron->setInterval(5);
										$cron->setSource('../../picontrol2-0/resources/plugins/temperature/resources/templates/temperature_monitoring.tmp.php');
										$cron->save();
									}
								
									$folder = PLUGINS_PATH.'/temp/logs/';
									
									foreach (@scandir($folder) as $file)
									{
										if ($file[0] != '.')
										{
											if (is_file($folder.'/'.$file) && substr($file, -8) == '.log.txt')
											{
												$fLogOld = file($folder.'/'.$file);
												
												$fLogNew = fopen('../../picontrol2-0/resources/log/plugin/temperature.temperature_'.substr($file, 0, -8).'.csv', 'w+');
												fwrite($fLogNew, str_replace('~', ',', implode('', $fLogOld)));
												fclose($fLogNew);
											}
										}
									}
									
									break;
							}
						}
					}
				}
			}
			
			$tpl->assign('content', errorMsg($errorMsg).'
				<div class="box">
						<div class="inner-header">
							<span>Aktualisierung auf Pi Control 2.0 - Schritt 3</span>
						</div>
						<div class="inner-bottom">
							Alle Daten wurden erfolgreich &uuml;bertragen. Im letzten Schritt der Aktualisierung wird das alte Pi Control gel&ouml;scht und durch Pi Control 2.0 ersetzt.
						</div>
						<div class="inner">
							<a href="setup.php?step=4"><button>Aktualisierung abschließen</button></a>
						</div>
					</div>
				</div>');
			
		} while (false);
	}
	elseif (isset($_GET['step']) && $_GET['step'] == '4')
	{
		$backupFolder = '/tmp/picontrol-backup-'.time().'/';
		
		mkdir($backupFolder);
		
		$folder = LOG_PATH;
		foreach (@scandir($folder) as $file)
		{
			if ($file[0] != '.')
			{
				if (is_file($folder.'/'.$file) && substr($file, -8) == '.log.txt')
					copy($folder.'/'.$file, $backupFolder.$file);
			}
		}
		
		$folder = PLUGINS_PATH.'/temp/logs/';
		foreach (@scandir($folder) as $file)
		{
			if ($file[0] != '.')
			{
				if (is_file($folder.'/'.$file) && substr($file, -8) == '.log.txt')
					copy($folder.'/'.$file, $backupFolder.$file);
			}
		}
		
		deleteFolder($config['paths']['main'].'/resources/');
		deleteFolder($config['paths']['main'].'/public_html/');
		
		$installations = array_filter(@scandir($config['paths']['main']), function($val)
		{
			if (substr($val, 0, 8) == 'install_')
				return true;
			
			return false;
		});
		
		foreach ($installations as $installation)
			deleteFolder($config['paths']['main'].'/'.$installation);
		
		rename($config['paths']['main'].'/picontrol2-0/api/', $config['paths']['main'].'/api/');
		rename($config['paths']['main'].'/picontrol2-0/resources/', $config['paths']['main'].'/resources/');
		rename($config['paths']['main'].'/picontrol2-0/public_html/', $config['paths']['main'].'/public_html/');
		rename($config['paths']['main'].'/picontrol2-0/index.php', $config['paths']['main'].'/index.php');
		deleteFolder($config['paths']['main'].'/picontrol2-0/');
		
		if (function_exists('apc_clear_cache')) apc_clear_cache();
		if (function_exists('opcache_reset')) opcache_reset();
		
		header('Location: ../../');
		exit();
	}
}

$tpl->draw('content_box');
?>