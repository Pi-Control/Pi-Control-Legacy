<?php
// CHECK IF FILE EXISTS
if (isset($_GET['id']))
{
	switch ($_GET['id'])
	{
		case 'raspi-config':
			echo 'raspi_available';
				break;
		case 'echo':
			echo 'successful';
				break;
	}
}
elseif (isset($argv) && count($argv) >= 2)
{
	switch ($argv[1])
	{
		case 'raspi-config':
			echo 'raspi_available';
				break;
		case 'echo':
			echo 'successful';
				break;
	}
}
?>