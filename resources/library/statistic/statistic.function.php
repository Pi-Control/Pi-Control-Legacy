<?php
if (!defined('PICONTROL')) exit();

function minNotNull($values)
{
	return min(array_diff(array_map('intval', $values), array(0)));
}

function getValueRow($value)
{
	return array('v' => floatval(str_replace(array("\n", ','), array('', '.'), $value)));
}

function calculatePeriods(&$info, $lastTime)
{
	$info['periods']['seven'] =	($lastTime - 604800) * 1000;
	$info['periods']['six'] =	($lastTime - 518400) * 1000;
	$info['periods']['five'] =	($lastTime - 432000) * 1000;
	$info['periods']['four'] =	($lastTime - 345600) * 1000;
	$info['periods']['three'] =	($lastTime - 259200) * 1000;
	$info['periods']['two'] =	($lastTime - 172800) * 1000;
	$info['periods']['one'] =	($lastTime - 86400) * 1000;
}
function calculateEmptyRows(&$arr, $columns, $firstTime, $lastTime, $cycle)
{
	$buffer = array();

	if ($lastTime < (time() - ($cycle * 60)))
	{
		for ($i = 0; $i < ceil(((time() - ($cycle * 60)) - $lastTime) / ($cycle * 60)); $i++)
		{
			$dummy = array($lastTime + (($i + 1) * ($cycle * 60)));
			
			for ($j = 1; $j < count($columns); $j++)
				$dummy[] = 0;
			
			$buffer[] = $dummy;
		}
		$arr['rows'] = array_merge($arr['rows'], $buffer);
	}
	
	$buffer = array();

	if (isset($arr['rows']) && count($arr['rows']) < (604800 / ($cycle * 60)))
	{
		for ($i = 0; $i < ((604800 / ($cycle * 60)) - count($arr['rows'])); $i++)
		{
			$dummy = array($firstTime - (($i + 1) * ($cycle * 60)));
			
			for ($j = 1; $j < count($columns); $j++)
				$dummy[] = 0;
			
			$buffer[] = $dummy;
		}
		$arr['rows'] = array_merge(array_reverse($buffer), $arr['rows']);
	}
}

function getRowsFromLog(&$arr, &$info, $log, $columns, $cycle)
{
	$lastTime = NULL;
	$firstTime = 0;
	$leapyear = -1;
	$leapyearSkip = 0;
	
	foreach ($log as $row)
	{
		if ($leapyearSkip != 0)
		{
			$leapyearSkip--;
			continue;
		}
		
		if ($leapyear != -1 && $leapyear > date('I', $row[0]))
		{
			$leapyear = 0;
			$leapyearSkip = (60/$cycle)-1;
			continue;
		}
		
		$leapyear = date('I', $row[0]);
		
		if ($lastTime !== NULL && ($lastTime + ($cycle * 60) + 100) < $row[0])
		{
			$skipped = round(($row[0] - ($lastTime + ($cycle * 60))) / ($cycle * 60));
			for ($i = 0; $i < $skipped; $i++)
			{
				$dummy = array($lastTime + (($i + 1) * ($cycle * 60)));
				
				for ($j = 1; $j < count($columns); $j++)
					$dummy[] = 0;
				
				$arr['rows'][] = $dummy;
			}
		}
		
		$dummy = array((int) $row[0]);
		
		for ($i = 1; $i < count($columns); $i++)
		{
			$rowFloat = 0;
			
			if (isset($columns[$i]['division']))
				$rowFloat = (float) round(str_replace(array("\n", ','), array('', '.'), $row[$i]) / $columns[$i]['division'], 2);
			elseif (isset($columns[$i]['multiplication']))
				$rowFloat = (float) round(str_replace(array("\n", ','), array('', '.'), $row[$i]) * $columns[$i]['multiplication'], 2);
			else
				$rowFloat = (float) $row[$i];
			
			if (!isset($info['min']) || $rowFloat < $info['min'])
				$info['min'] = $rowFloat;
			
			if (!isset($info['max']) || $rowFloat > $info['max'])
				$info['max'] = $rowFloat;
			
			$dummy[] = $rowFloat;
		}
		
		$arr['rows'][] = $dummy;
		
		if ($firstTime == 0)
			$firstTime = $row[0];
		
		$lastTime = $row[0];
	}
	
	calculateEmptyRows($arr, $columns, $firstTime, $lastTime, $cycle);
	calculatePeriods($info, $lastTime);
}

function convertForGoogleChart($rows)
{
	foreach ($rows as $index => $row)
	{
		$dummy = array('c' => array(array('v' => 'Date('.date('Y,'.(date('m', $row[0])-1).',d,H,i', $row[0]).')')));
		
		unset($row[0]);
		
		foreach ($row as $inde2 => $row2)
			$dummy['c'][] = array('v' => $row2);
		
		$rows[$index] = $dummy;
	}
	
	return $rows;
}
?>