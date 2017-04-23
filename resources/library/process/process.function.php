<?php

function getReadableStatus($status)
{
	switch (substr($status, 0, 1))
	{
		case 'D':
			return _t('Ununterbrechbar');
		case 'R':
			return _t('L&auml;uft');
		case 'S':
			return _t('Schl&auml;ft');
		case 'T':
			return _t('Gestoppt');
		case 'Z':
			return _t('Zombie');
	}
}

function getSecondsFromTime($time)
{
	$split = preg_split('#[-:]+#', $time);
	
	if (count($split) == 4)
		return $split[0]*86400 + $split[1]*3600 + $split[2]*60 + $split[3];
	elseif (count($split) == 3)
		return $split[0]*3600 + $split[1]*60 + $split[2];
	
	return $split[0]*60 + $split[1];
}

function getStartTimeFromTime($time)
{
	return time() - getSecondsFromTime($time);
}
?>