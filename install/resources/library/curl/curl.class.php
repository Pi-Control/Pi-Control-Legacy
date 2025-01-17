<?php
if (!defined('PICONTROL')) exit();

defined('HTTP_GET')		or define('HTTP_GET', 'GET');
defined('HTTP_POST')	or define('HTTP_POST', 'POST');
defined('HTTP_PUT')		or define('HTTP_PUT', 'PUT');
defined('HTTP_DELETE')	or define('HTTP_DELETE', 'DELETE');

class cURL
{
	private $handler, $result, $info, $method = HTTP_GET, $url, $parameter = array(), $parameterRaw = '', $header = array(), $statusCode = 0, $customStatusCode = 0;
	
	public function __construct($url, $method = HTTP_GET, $parameter = NULL)
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
?>