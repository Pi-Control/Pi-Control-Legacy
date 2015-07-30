<?php
function minNotNull($values)
{
	return min(array_diff(array_map('intval', $values), array(0)));
}

function getValueRow($value)
{
	return array('v' => floatval(str_replace(array("\n", ','), array('', '.'), $value)));
}

function calculateEmptyRows(&$arr, $type, $firstTime)
{
	$buffer = array();

	if (isset($arr['rows']) && count($arr['rows']) < 2016)
	{
		for ($i = 0; $i < (2016 - count($arr['rows'])); $i++)
		{
			if ($type == 'network')
			{
				$buffer[] = array('c' =>
								array(
									array('v' => 'Date('.date('Y,'.(date('m', $firstTime-(($i+1) * 300))-1).',d,H,i', $firstTime-(($i+1) * 300)).')'),
									getValueRow(0),
									getValueRow(0)
								)
							);
			}
			else
			{
				$buffer[] = array('c' =>
								array(
									array('v' => 'Date('.date('Y,'.(date('m', $firstTime-(($i+1) * 300))-1).',d,H,i', $firstTime-(($i+1) * 300)).')'),
									getValueRow(0)
								)
							);
			}
		}
		$arr['rows'] = array_merge($buffer, $arr['rows']);
	}
}

function calculatePeriods(&$info, $lastTime)
{
	$info['periods']['seven'] = ($lastTime - 604800) * 1000;
	$info['periods']['six'] = ($lastTime - 518400) * 1000;
	$info['periods']['five'] = ($lastTime - 432000) * 1000;
	$info['periods']['four'] = ($lastTime - 345600) * 1000;
	$info['periods']['three'] = ($lastTime - 259200) * 1000;
	$info['periods']['two'] = ($lastTime - 172800) * 1000;
	$info['periods']['one'] = ($lastTime - 86400) * 1000;
}

function getRowsFromLog(&$arr, &$info, $log, $type)
{
	$lastTime = NULL;
	$firstTime = 0;
	
	foreach ($log as $row)
	{
		if ($lastTime !== NULL && $lastTime > $row[0])
			continue;
		
		if ($lastTime !== NULL && $lastTime+400 < $row[0])
		{
			$skipped = round(($row[0] - $lastTime)/300);
			for ($i = 0; $i < $skipped; $i++)
			{
				if ($type == 'network')
				{
					$arr['rows'][]['c'] = array(
						array('v' => 'Date('.date('Y,'.(date('m', $lastTime+(($i+1) * 300))-1).',d,H,i', $lastTime+(($i+1) * 300)).')'),
						getValueRow(0),
						getValueRow(0)
					);
				}
				else
				{
					$arr['rows'][]['c'] = array(
						array('v' => 'Date('.date('Y,'.(date('m', $lastTime+(($i+1) * 300))-1).',d,H,i', $lastTime+(($i+1) * 300)).')'),
						getValueRow(0)
					);
				}
			}
		}
		else
		{
			if ($type == 'network')
			{
				$info['up'][] = floatval(round(str_replace(array("\n", ','), array('', '.'), $row[1])/1048576,2));
				$info['down'][] = floatval(round(str_replace(array("\n", ','), array('', '.'), $row[2])/1048576,2));
				$arr['rows'][]['c'] = array(
										array('v' => 'Date('.date('Y,'.(date('m', $row[0])-1).',d,H,i', $row[0]).')'),
										getValueRow(round(str_replace(array("\n", ','), array('', '.'), $row[1])/1048576,2)),
										getValueRow(round(str_replace(array("\n", ','), array('', '.'), $row[2])/1048576,2))
									);
			}
			else
			{
				$info['values'][] = floatval(str_replace(array("\n", ','), array('', '.'), $row[1]));
				$arr['rows'][]['c'] = array(
										array('v' => 'Date('.date('Y,'.(date('m', $row[0])-1).',d,H,i', $row[0]).')'),
										getValueRow($row[1])
									);
			}
			
			if ($firstTime == 0)
				$firstTime = $row[0];
		}
		
		$lastTime = $row[0];
	}
	
	calculateEmptyRows($arr, $type, $firstTime);
	calculatePeriods($info, $lastTime);
}
?>