<?php
(include_once realpath(dirname(__FILE__)).'/../../main_config.php') or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0000');
(include_once LIBRARY_PATH.'/main/classes.php')						or die('Fehler beim Laden der Seite. Konnte Konfigurationen nicht laden. Fehlercode: 0x0001');

function minNotNull($values)
{
	return min(array_diff(array_map('intval', $values), array(0)));
}

$log = new Logging();
$log->setFile(LOG_PATH.'/'.$_GET['log'].'.log.txt');


switch ($_GET['type'])
{
	case 'coretemp': // CPU-Temperatur
	$arr = array();
	$arr['cols'][] = array('id' => '', 'label' => 'Zeit', 'type' => 'datetime');
	$arr['cols'][] = array('id' => '', 'label' => 'Temperatur', 'type' => 'number');

	$lastTime = NULL;
	$avg = array();
	foreach ($log->getAll() as $row)
	{
		if ($lastTime !== NULL && $lastTime > $row[0])
			continue;

		if ($lastTime !== NULL && $lastTime+400 < $row[0])
		{
			$skipped = round(($row[0] - $lastTime)/300);
			for ($i = 0; $i < $skipped; $i++)
			{
				$arr['rows'][]['c'] = array(
					array('v' => 'Date('.date('Y,'.(date('m', $lastTime+(($i+1) * 300))-1).',d,H,i', $lastTime+(($i+1) * 300)).')'),
					array('v' => floatval(0))
				);
			}
		}
		else
		{
			$avg[] = floatval(str_replace(array("\n", ','), array('', '.'), $row[1]));
			$arr['rows'][]['c'] = array(
					array('v' => 'Date('.date('Y,'.(date('m', $row[0])-1).',d,H,i', $row[0]).')'),
					array('v' => floatval(str_replace(array("\n", ','), array('', '.'), $row[1])))
				);
		}

		$lastTime = $row[0];
	}

	$arr['rows'] = array_slice($arr['rows'], -2016);
	$arr['max'] = round(max($avg) * 1.05);
	$arr['min'] = round(min($avg) * 0.95);
		break;

	case 'cpuload': // CPU-Auslastung
	$arr = array();
	$arr['cols'][] = array('id' => '', 'label' => 'Zeit', 'type' => 'datetime');
	$arr['cols'][] = array('id' => '', 'label' => 'Auslastung', 'type' => 'number');

	$lastTime = NULL;
	$avg = array();
	foreach ($log->getAll() as $row)
	{
		if ($lastTime !== NULL && $lastTime > $row[0])
			continue;

		if ($lastTime !== NULL && $lastTime+400 < $row[0])
		{
			$skipped = round(($row[0] - $lastTime)/300);
			for ($i = 0; $i < $skipped; $i++)
			{
				$arr['rows'][]['c'] = array(
					array('v' => 'Date('.date('Y,'.(date('m', $lastTime+(($i+1) * 300))-1).',d,H,i', $lastTime+(($i+1) * 300)).')'),
					array('v' => floatval(0))
				);
			}
		}
		else
		{
			$avg[] = floatval(str_replace(array("\n", ','), array('', '.'), $row[1]));
			$arr['rows'][]['c'] = array(
					array('v' => 'Date('.date('Y,'.(date('m', $row[0])-1).',d,H,i', $row[0]).')'),
					array('v' => floatval(str_replace(array("\n", ','), array('', '.'), $row[1])))
				);
		}

		$lastTime = $row[0];
	}

	$arr['rows'] = array_slice($arr['rows'], -2016);
	$arr['max'] = 100;
	$arr['min'] = 0;
		break;

	case 'ram': // Arbeitsspeicher
	$arr = array();
	$arr['cols'][] = array('id' => '', 'label' => 'Zeit', 'type' => 'datetime');
	$arr['cols'][] = array('id' => '', 'label' => 'Auslastung', 'type' => 'number');

	$lastTime = NULL;
	$avg = array();
	foreach ($log->getAll() as $row)
	{
		if ($lastTime !== NULL && $lastTime > $row[0])
			continue;


		if ($lastTime !== NULL && $lastTime+400 < $row[0])
		{
			$skipped = round(($row[0] - $lastTime)/300);
			for ($i = 0; $i < $skipped; $i++)
			{
				$arr['rows'][]['c'] = array(
					array('v' => 'Date('.date('Y,'.(date('m', $lastTime+(($i+1) * 300))-1).',d,H,i', $lastTime+(($i+1) * 300)).')'),
					array('v' => floatval(0))
				);
			}
		}
		else
		{
			$avg[] = floatval(str_replace(array("\n", ','), array('', '.'), $row[1]));
			$arr['rows'][]['c'] = array(
					array('v' => 'Date('.date('Y,'.(date('m', $row[0])-1).',d,H,i', $row[0]).')'),
					array('v' => floatval(str_replace(array("\n", ','), array('', '.'), $row[1])))
				);
		}

		$lastTime = $row[0];
	}

	$arr['rows'] = array_slice($arr['rows'], -2016);
	$arr['max'] = 100;
	$arr['min'] = 0;
		break;

	case 'network': // Netzwerk
	$arr = array();
	$arr['cols'][] = array('id' => '', 'label' => 'Zeit', 'type' => 'datetime');
	$arr['cols'][] = array('id' => '', 'label' => 'Gesendet', 'type' => 'number');
	$arr['cols'][] = array('id' => '', 'label' => 'Empfangen', 'type' => 'number');

	$lastTime = NULL;
	$avgUp = array();
	$avgDown = array();
	foreach ($log->getAll() as $row)
	{
		if ($lastTime !== NULL && $lastTime > $row[0])
			continue;


		if ($lastTime !== NULL && $lastTime+400 < $row[0])
		{
			$skipped = round(($row[0] - $lastTime)/300);
			for ($i = 0; $i < $skipped; $i++)
			{
				$arr['rows'][]['c'] = array(
					array('v' => 'Date('.date('Y,'.(date('m', $lastTime+(($i+1) * 300))-1).',d,H,i', $lastTime+(($i+1) * 300)).')'),
					array('v' => floatval(0)),
					array('v' => floatval(0))
				);
			}
		}
		else
		{
			$avgUp[] = floatval(str_replace(array("\n", ','), array('', '.'), round($row[1]/1048576,2)));
			$avgDown[] = floatval(str_replace(array("\n", ','), array('', '.'), round($row[2]/1048576,2)));
			$arr['rows'][]['c'] = array(
					array('v' => 'Date('.date('Y,'.(date('m', $row[0])-1).',d,H,i', $row[0]).')'),
					array('v' => floatval(str_replace(array("\n", ','), array('', '.'), round($row[1]/1048576,2)))),
					array('v' => floatval(str_replace(array("\n", ','), array('', '.'), round($row[2]/1048576,2))))
				);
		}

		$lastTime = $row[0];
	}

	$arr['rows'] = array_slice($arr['rows'], -2016);
	$arr['max'] = round(((max($avgUp) > max($avgDown)) ? max($avgUp) : max($avgDown)) * 1.10);
	$arr['min'] = round(((min($avgUp) > min($avgDown)) ? min($avgDown) : min($avgUp)) * 0.90);
		break;
}

if (file_exists(LOG_PATH.'/'.$_GET['log'].'.log.txt') && is_file(LOG_PATH.'/'.$_GET['log'].'.log.txt') && filesize(LOG_PATH.'/'.$_GET['log'].'.log.txt') == 0)
	header("HTTP/1.0 412");
else
	print(json_encode($arr, JSON_NUMERIC_CHECK));
?>