function show_error(msg)
{
	$('div.dummy-1').html('<strong class="red">' + msg + '</strong>');
}

$(document).on('click', 'a[href=#refresh]', function(e)
{
	if ($('a[href=#refresh]').css('opacity') == 1)
	{
		var _this = this;
		var _interface = $(this).attr('name');
		
		$('a[href=#refresh]').not(this).animate({opacity: 0.2}, 300);
		$(this).find('span').addClass('rotate-icon');
		
		$('div.dummy-1 .inner-header span').text('Status (' + _interface + ')');
		$('div.dummy-1 .inner').html('<strong>Das Interface wird neu gestartet...</strong>');
		$('div.dummy-1').slideDown('fast');
		
		$.post('api/v1/network_configuration_interface.php', { interface: _interface }, function(data)
		{
			if (data.status == 200)
			{
				$('div.dummy-1 .inner').html('<strong class="green">Das Interface wurde neu gestartet.</strong>');
				$('a[href=#refresh]').not(_this).animate({opacity: 1}, 300);
				$(_this).find('span').removeClass('rotate-icon');
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