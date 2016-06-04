function show_error(msg)
{
	jQuery('div.dummy-1').html('<strong class="red">' + msg + '</strong>');
}

jQuery(document).on('click', 'a[href=#refresh]', function(e)
{
	if (jQuery('a[href=#refresh]').css('opacity') == 1)
	{
		var _this = this;
		var _interface = jQuery(this).attr('name');
		
		jQuery('a[href=#refresh]').not(this).animate({opacity: 0.2}, 300);
		jQuery(this).find('span').addClass('rotate-icon');
		
		jQuery('div.dummy-1 .inner-header span').text('Status (' + _interface + ')');
		jQuery('div.dummy-1 .inner').html('<strong>Das Interface wird neu gestartet...</strong>');
		jQuery('div.dummy-1').slideDown('fast');
		
		jQuery.post('api/v1/network_configuration_interface.php', { interface: _interface }, function(data)
		{
			if (data.status == 200)
			{
				jQuery('div.dummy-1 .inner').html('<strong class="green">Das Interface wurde neu gestartet.</strong>');
				jQuery('a[href=#refresh]').not(_this).animate({opacity: 1}, 300);
				jQuery(_this).find('span').removeClass('rotate-icon');
			}
			else
			{
				show_error('Es ist ein unerwarteter Fehler aufgetreten!');
				return false;
			}
		}).fail(function(e)
		{
			show_error('Es ist ein Fehler aufgetreten! Vermutlich wurde die Verbindung getrennt.');
			return false;
		});
	}
	else
		alert('Es kann nur ein Interface zeitgleich neu gestartet werden.');
	
	return false;
});