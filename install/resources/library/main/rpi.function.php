<?php
if (!defined('PICONTROL')) exit();

function rpi_getRuntime()
{
	$runtime = trim(@shell_exec('cat /proc/uptime | awk -F \'.\' \'{print $1}\''));
	return $runtime;
}

function rpi_getDistribution()
{
	$distribution = trim(@shell_exec('cat /etc/issue | cut -d " " -f 1-3'));
	
	if ($distribution == '')
	{
		$distributionString = @shell_exec('cat /etc/*-release | grep PRETTY_NAME');
		
		preg_match('/.*="([\w\s\d\/]*).*"/i', $distributionString, $match);
		
		if (count($match) == 2)
			$distribution = trim($match[1]);
		else
			$distribution = 'Unknown';
	}
	
	return $distribution;
}
?>