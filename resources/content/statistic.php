<?php
$tpl->setHeaderTitle(_t('Statistik'));

$folder = LOG_PATH.'/statistic';
$fileArray = array();
$logArray = array();
$hiddenStatistics = array_filter(explode('~', $tpl->getConfig('main:statistic.hidden', '')));

foreach (@scandir($folder) as $file)
{
	if ($file[0] != '.')
	{
		if (is_file($folder.'/'.$file) && substr($file, -4) == '.csv')
			$fileArray[] = $file;
	}
}

foreach ($fileArray as $file_)
{
	if (substr($file_ , 0, -4) == 'coretemp' && array_search('coretemp', $hiddenStatistics) === false)
	{
		$logArray[] = array('log' => 'coretemp',
							'label' => 'CPU-Temperatur',
							'type' => 'coretemp',
							'title' => 'Grad Celsius',
							'unit' => '  °C',
							'columns' => array(1));
	}
	if (substr($file_ , 0, -4) == 'cpuload' && array_search('cpuload', $hiddenStatistics) === false)
	{
		$logArray[] = array('log' => 'cpuload',
							'label' => 'CPU-Auslastung',
							'type' => 'cpuload',
							'title' => 'Auslastung %',
							'unit' => '  %',
							'columns' => array(1));
	}
	if (substr($file_ , 0, -4) == 'ram' && array_search('ram', $hiddenStatistics) === false)
	{
		$logArray[] = array('log' => 'ram',
							'label' => 'RAM-Auslastung',
							'type' => 'ram',
							'title' => 'Auslastung %',
							'unit' => '  %',
							'columns' => array(1));
	}
	elseif (substr($file_ , 0, 8) == 'network_' && array_search(substr($file_ , 0, -4), $hiddenStatistics) === false)
	{
		$logArray[] = array('log' => substr($file_, 0, -4),
							'label' => substr($file_ , 8, -4),
							'type' => 'network',
							'title' => 'Traffic (MB)',
							'unit' => ' MB',
							'columns' => array(1,2));
	}
}

$tpl->assign('statistic_path', str_replace(dirname($_SERVER['SCRIPT_FILENAME']).'/', '', LIBRARY_PATH).'/statistic');
$tpl->assign('logArrayCount', count($fileArray));
$tpl->assign('logArray', $logArray);

$tpl->draw('statistic');
?>