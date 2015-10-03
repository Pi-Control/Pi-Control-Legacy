<?php
class API
{
	// Startzeit
	private $startTime = 0;
	
	// Statuscode
	private $status = 200;
	
	// Daten
	private $dataArray = array();
	
	// Fehler
	private $error = array();
	
	/**
	 * Konstrukter, welcher die Startzeit für die Lauftzeit festlegt.
	 *
	 * <code>$api = new API;</code>
	 */
	
	function __construct()
	{
		$this->startTime = microtime(true);
	}
	
	/**
	 * Fügt ein Element hinzu.
	 *
	 * <code>$api->addData('Key', 'Wert');</code>
	 *
	 * @param string $key Schlüsselwert
	 * @param string|array|bool $value Wert
	 * @return bool
	 */
	
	public function addData($key, $value)
	{
		if (!empty($this->error))
			return false;
		
		$dummyStatus = $this->status;
		$this->status = 500;
		
		if (($key = trim($key)) == '')
			return false;
		
		$splits = explode('.', $key);
		
		switch (count($splits))
		{
			case 1:
				$this->dataArray[$splits[0]] = $value;
				break;
			case 2:
				if ($splits[1] == '')
					$this->dataArray[$splits[0]][] = $value;
				else
					$this->dataArray[$splits[0]][$splits[1]] = $value;
				break;
			case 3:
				if ($splits[2] == '')
					$this->dataArray[$splits[0]][$splits[1]][] = $value;
				else
					$this->dataArray[$splits[0]][$splits[1]][$splits[2]] = $value;
				break;
			default:
				return false;
		}
		
		$this->status = $dummyStatus;
		
		return true;
	}
	
	/**
	 * Setzt den Statuscode.
	 *
	 * <code>$api->setStatus(200);</code>
	 *
	 * @param int $status Statuscode
	 * @return bool
	 */
	
	public function setStatus($status)
	{
		if (!is_int($status))
			return false;
			
		$this->status = $status;
		
		return true;
	}
	
	/**
	 * Setzt eine Fehlermeldung.
	 *
	 * <code>$api->setError('client', 'Falsche Benutzereingabe!', 500);</code>
	 *
	 * @param string $type Fehlertyp
	 * @param string $message Fehlernachricht
	 * @param int $status Statuscode
	 * @return bool
	 */
	
	public function setError($type, $message, $status = 500)
	{
		if (($type = trim($type)) == '')
			return false;
		
		if (($message = trim($message)) == '')
			return false;
		
		if (!is_int($status))
			return false;
		
		$this->error = array('type' => $type, 'message' => $message);
		$this->status = $status;
		
		return true;
	}
	
	/**
	 * Erzeugt das fertige JSON und gibt dieses anschließend aus.
	 *
	 * <code>$api->display(true);</code>
	 *
	 * @param bool $prettyPrint Eingerückte Ausgabe des JSON
	 * @return bool Ausgabe erfolgt mit "echo".
	 */
	
	public function display($prettyPrint = false)
	{
		header('Content-Type: application/json');
		
		$executionTime = microtime(true) - $this->startTime;
		
		if (empty($this->error))
			echo json_encode(array('data' => $this->dataArray, 'executionTime' => $executionTime, 'status' => $this->status), ($prettyPrint != false) ? JSON_PRETTY_PRINT : 0);
		else
			echo json_encode(array('error' => $this->error, 'executionTime' => $executionTime, 'status' => $this->status), ($prettyPrint != false) ? JSON_PRETTY_PRINT : 0);
		
		return true;
	}
}
?>