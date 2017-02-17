<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle(_t('Einstellungen zur &Uuml;bersicht'));

if (isset($_POST['submit-main']) && $_POST['submit-main'] != '')
{
	if (isset($_POST['overview-interval']) && is_numeric($_POST['overview-interval']) && $_POST['overview-interval'] >= 1 && $_POST['overview-interval'] <= 9999)
	{
		setConfig('main:overview.interval', $_POST['overview-interval']);
		$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'), true, 10);
	}
	else
		$tpl->msg('error', _t('Fehler'), _t('Bitte vergebe f&uuml;r den Intervall einen Wert zwischen 1 und 9999.'), true, 10);
	
	if (isset($_POST['show-devices']) && $_POST['show-devices'] == 'checked')
		setConfig('main:overview.showDevices', 'true');
	else
		setConfig('main:overview.showDevices', 'false');
}

if (isset($_POST['submit-weather']) && $_POST['submit-weather'] != '')
{
	if (isset($_POST['weather-service'], $_POST['weather-location-country'], $_POST['weather-location-type']) &&
		in_array($_POST['weather-service'], array('openweathermap', 'yahoo', 'wunderground', 'darksky', 'yr')) &&
		in_array($_POST['weather-location-country'], array('germany', 'austria', 'swiss', 'uk')) &&
		((isset($_POST['weather-service-token']) && in_array($_POST['weather-service'], array('openweathermap', 'wunderground', 'darksky')) && ($pWeatherServiceToken = trim($_POST['weather-service-token'])) != '') || in_array($_POST['weather-service'], array('yahoo', 'yr'))) &&
		(($_POST['weather-location-type'] == 'postcode' && isset($_POST['weather-location-postcode-text']) && $_POST['weather-location-postcode-text'] != '') || ($_POST['weather-location-type'] == 'city' && isset($_POST['weather-location-city-text']) && ($cityText = trim($_POST['weather-location-city-text'])) != '') || ($_POST['weather-location-type'] == 'coordinates' && isset($_POST['weather-location-coordinates-latitude-text'], $_POST['weather-location-coordinates-longitude-text']) && ($coordinatesLatitudeText = trim($_POST['weather-location-coordinates-latitude-text'])) != '' && ($coordinatesLongitudeText = trim($_POST['weather-location-coordinates-longitude-text'])) != '')))
	{
		setConfig('main:weather.service', $_POST['weather-service']);
		setConfig('main:weather.country', $_POST['weather-location-country']);
		setConfig('main:weather.type', $_POST['weather-location-type']);
		
		if (isset($pWeatherServiceToken) && ((strlen($pWeatherServiceToken) == 32 && in_array($_POST['weather-service'], array('openweathermap', 'darksky'))) || (strlen($pWeatherServiceToken) == 16 && in_array($_POST['weather-service'], array('wunderground')))))
			setConfig('main:weather.serviceToken', $pWeatherServiceToken);
		elseif (in_array($_POST['weather-service'], array('openweathermap', 'wunderground', 'darksky')))
			$tpl->msg('error', _t('Fehler'), _t('Leider ist der angegebene API-Schl&uuml;ssel zu kurz!'), true, 10);
		else
			setConfig('main:weather.serviceToken', '');
		
		if ($_POST['weather-location-type'] == 'postcode')
		{
			if (in_array($_POST['weather-location-country'], array('germany', 'austria', 'swiss')))
			{
				if (in_array(strlen($_POST['weather-location-postcode-text']), array(4, 5)) && $_POST['weather-location-postcode-text'] >= 1 && $_POST['weather-location-postcode-text'] <= 99999)
					setConfig('main:weather.postcode', $_POST['weather-location-postcode-text']);
				else
					$tpl->msg('error', _t('Fehler'), _t('Leider ist die angegebene Postleitzahl ung&uuml;ltig!'), true, 10);
			}
			elseif (in_array($_POST['weather-location-country'], array('uk')))
			{
				if (in_array(strlen($_POST['weather-location-postcode-text']), array(5, 6, 7, 8)) && preg_match('/^([A-Z]{1,2}[0-9][A-Z]?[0-9]?)( )?([0-9]{1}[A-Z]{2})$/', $_POST['weather-location-postcode-text']))
					setConfig('main:weather.postcode', $_POST['weather-location-postcode-text']);
				else
					$tpl->msg('error', _t('Fehler'), _t('Leider ist die angegebene Postleitzahl ung&uuml;ltig!'), true, 10);
			}
		}
		elseif ($_POST['weather-location-type'] == 'city')
		{
			if (strlen($cityText) >= 3 && preg_match('/^[A-Za-zÄÖÜäöü \(\)\.\-\/]+$/', $cityText))
				setConfig('main:weather.city', $cityText);
			else
				$tpl->msg('error', _t('Fehler'), _t('Leider ist der angegebene Stadtname ung&uuml;ltig!'), true, 10);
		}
		elseif ($_POST['weather-location-type'] == 'coordinates')
		{
			if (preg_match('/^-?[0-9]{1,2}([\.|,][0-9]{1,6})?$/', $coordinatesLatitudeText) && preg_match('/^-?[0-9]{1,2}([\.|,][0-9]{1,6})?$/', $coordinatesLongitudeText))
			{
				setConfig('main:weather.latitude', str_replace(',', '.', $coordinatesLatitudeText));
				setConfig('main:weather.longitude', str_replace(',', '.', $coordinatesLongitudeText));
			}
			else
				$tpl->msg('error', _t('Fehler'), _t('Leider sind die angegebenen Koordinaten ung&uuml;ltig!'), true, 10);
		}
		
		if ($tpl->msgExists(10) === false)
		{
			setConfig('main:weather.activation', (isset($_POST['weather-activation']) && $_POST['weather-activation'] == 'checked') ? 'true' : 'false');
			
			if ($_POST['weather-service'] == 'yr')
				setConfig('main:weather.yrCache', '');
			
			if ($_POST['weather-service'] == 'wunderground')
				setConfig('main:weather.wundergroundCache', '');
			
			$tpl->msg('success', _t('Einstellungen gespeichert'), _t('Die Einstellungen wurden erfolgreich gespeichert.'));
		}
	}
	else
		$tpl->msg('error', _t('Fehler'), _t('Bitte f&uuml;lle alle ben&ouml;tigten Felder aus!'));
}

$tpl->assign('main-overview-interval', getConfig('main:overview.interval', '30'));
$tpl->assign('main-show-devices', getConfig('main:overview.showDevices', 'true'));
$tpl->assign('weather-activation', (getConfig('main:weather.activation', 'false') == 'true') ? true : false);
$tpl->assign('weather-service', getConfig('main:weather.service', 'openweathermap'));
$tpl->assign('weather-service-token', getConfig('main:weather.serviceToken', ''));
$tpl->assign('weather-country', getConfig('main:weather.country', 'germany'));
$tpl->assign('weather-type', getConfig('main:weather.type', 'postcode'));
$tpl->assign('weather-city', getConfig('main:weather.city', ''));
$tpl->assign('weather-postcode', getConfig('main:weather.postcode', ''));
$tpl->assign('weather-coordinates-latitude', getConfig('main:weather.latitude', ''));
$tpl->assign('weather-coordinates-longitude', getConfig('main:weather.longitude', ''));
$tpl->assign('weather-info', getWeather());

$tpl->draw('settings/overview');
?>