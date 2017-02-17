function toggleVisibilityWeatherLocationTyps(service)
{
	switch (service)
	{
		case 'darksky':
			jQuery('input[name=weather-service-token]').attr('maxlength', 32).show();
			jQuery('.weather-location-type-postcode').hide();
			jQuery('.weather-location-type-city').hide();
			jQuery('.weather-location-type-coordinates').show();
				break;
		case 'openweathermap':
			jQuery('input[name=weather-service-token]').attr('maxlength', 32).show();
			jQuery('.weather-location-type-postcode').show();
			jQuery('.weather-location-type-city').show();
			jQuery('.weather-location-type-coordinates').hide();
				break;
		case 'wunderground':
			jQuery('input[name=weather-service-token]').attr('maxlength', 16).show();
			jQuery('.weather-location-type-postcode').hide();
			jQuery('.weather-location-type-city').show();
			jQuery('.weather-location-type-coordinates').show();
				break;
		case 'yahoo':
			jQuery('input[name=weather-service-token]').hide();
			jQuery('.weather-location-type-postcode').show();
			jQuery('.weather-location-type-city').show();
			jQuery('.weather-location-type-coordinates').hide();
				break;
		case 'yr':
			jQuery('input[name=weather-service-token]').hide();
			jQuery('.weather-location-type-postcode').show();
			jQuery('.weather-location-type-city').show();
			jQuery('.weather-location-type-coordinates').hide();
				break;
	}
}

jQuery(document).on('change', 'select[name="weather-service"]', function (e)
{
	var service = jQuery(this).val();
	
	jQuery('.weather-service-link').attr('href', jQuery(this).find('option:selected').data('url'));
	jQuery('input[name=weather-service-token]').val('');
	toggleVisibilityWeatherLocationTyps(service);
	
	switch (service)
	{
		case 'darksky':
			jQuery('#cb-weather-location-coordinates').prop('checked', true );
				break;
		case 'openweathermap':
			jQuery('#cb-weather-location-postcode').prop('checked', true );
				break;
		case 'wunderground':
			jQuery('#cb-weather-location-city').prop('checked', true );
				break;
		case 'yahoo':
			jQuery('#cb-weather-location-postcode').prop('checked', true );
				break;
		case 'yr':
			jQuery('#cb-weather-location-postcode').prop('checked', true );
				break;
	}
});

jQuery(function()
{
	jQuery('.weather-service-link').attr('href', jQuery('select[name="weather-service"]').find('option:selected').data('url'));
	toggleVisibilityWeatherLocationTyps(jQuery('select[name="weather-service"]').val());
});