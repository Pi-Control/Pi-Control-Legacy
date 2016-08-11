<?php
if (!defined('PICONTROL')) exit();

function getExtreme($type, $info)
{
	if ($type == 'min')
	{
		$mins = array();
		
		foreach ($info as $key => $value)
		{
			if (is_int($key))
				$mins[] = min($value);
		}
		
		return min($mins);
	}
	elseif ($type == 'max')
	{
		$maxs = array();
		
		foreach ($info as $key => $value)
		{
			if (is_int($key))
				$maxs[] = max($value);
		}
		
		return max($maxs);
	}
}

function minNotNull($values)
{
	return min(array_diff(array_map('intval', $values), array(0)));
}

function getValueRow($value)
{
	return array('v' => floatval(str_replace(array("\n", ','), array('', '.'), $value)));
}

function calculateEmptyRows(&$arr, $columns, $firstTime, $cycle)
{
	$buffer = array();

	if (isset($arr['rows']) && count($arr['rows']) < (604800/($cycle*60)))
	{
		for ($i = 0; $i < ((604800/($cycle*60)) - count($arr['rows'])); $i++)
		{
			$dummy = array('c' =>
							array(
								array('v' => 'Date('.date('Y,'.(date('m', $firstTime-(($i+1) * ($cycle*60)))-1).',d,H,i', $firstTime-(($i+1) * ($cycle*60))).')')
							)
						);
			
			for ($j = 1; $j < count($columns); $j++)
				$dummy['c'][] = getValueRow(0);
			
			$buffer[] = $dummy;
		}
		$arr['rows'] = array_merge(array_reverse($buffer), $arr['rows']);
	}
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
			$leapyearSkip = 11;
			continue;
		}
		
		$leapyear = date('I', $row[0]);
		
		if ($lastTime !== NULL && $lastTime+($cycle*60)+100 < $row[0])
		{
			$skipped = round(($row[0]-($lastTime+($cycle*60)))/300);
			for ($i = 0; $i < $skipped; $i++)
			{
				$dummy = array(
					array('v' => 'Date('.date('Y,'.(date('m', $lastTime+(($i+1) * ($cycle * 60)))-1).',d,H,i', $lastTime+(($i+1) * ($cycle * 60))).')')
				);
				
				for ($j = 1; $j < count($columns); $j++)
					$dummy[] = getValueRow(0);
				
				$arr['rows'][]['c'] = $dummy;
			}
		}
		else
		{
			for ($i = 1; $i < count($columns); $i++)
			{
				if (isset($columns[$i]['division']))
					$info[$i-1][] = floatval(round(str_replace(array("\n", ','), array('', '.'), $row[$i])/$columns[$i]['division'], 2));
				elseif (isset($columns[$i]['multiplication']))
					$info[$i-1][] = floatval(round(str_replace(array("\n", ','), array('', '.'), $row[$i])*$columns[$i]['multiplication'], 2));
				else
					$info[$i-1][] = floatval(str_replace(array("\n", ','), array('', '.'), $row[$i]));
			}
			
			$dummy = array(
				array('v' => 'Date('.date('Y,'.(date('m', $row[0])-1).',d,H,i', $row[0]).')')
			);
			
			for ($i = 1; $i < count($columns); $i++)
			{
				if (isset($columns[$i]['division']))
					$dummy[] = getValueRow(round(str_replace(array("\n", ','), array('', '.'), $row[$i])/$columns[$i]['division'], 2));
				elseif (isset($columns[$i]['multiplication']))
					$dummy[] = getValueRow(round(str_replace(array("\n", ','), array('', '.'), $row[$i])*$columns[$i]['multiplication'], 2));
				else
					$dummy[] = getValueRow($row[$i]);
			}
			
			$arr['rows'][]['c'] = $dummy;
			
			if ($firstTime == 0)
				$firstTime = $row[0];
		}
		
		$lastTime = $row[0];
	}
	
	calculateEmptyRows($arr, $columns, $firstTime, $cycle);
	calculatePeriods($info, $lastTime);
}
?>