<?php

function getReadableStatus($status)
{
	switch ($status)
	{
		case 'D':
			return _t('Ununterbrechbar');
		case 'R':
			return _t('Aktiv');
		case 'S':
			return _t('Inaktiv');
		case 'T':
			return _t('Angehalten');
		case 'Z':
			return _t('Zombie');
	}
}

?>