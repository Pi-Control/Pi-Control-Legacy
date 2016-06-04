function show_error(msg)
{
	console.log(msg);
	jQuery('div.dummy-3 strong span').html(msg.data.errorMsg);
	jQuery('div.dummy-2').slideUp('fast');
	jQuery('div.dummy-3').slideDown('fast');
}

jQuery(document).on('click', 'input[name=submit]', function(e)
{
	if (jQuery('input[name=password]').length == 1 && jQuery('input[name=password]').val().length < 8 && jQuery('input[name=password]').attr('data') == null)
	{
		alert('Das Passwort sollte mindestens 8 Zeichen betragen.');
		return false;
	}
	
	if (jQuery('input[name=password]').length == 1)
		jQuery('input[name=password]').prop('disabled', true);
	
	jQuery('div.dummy-1').slideUp('fast');
	jQuery('div.dummy-2').slideDown('fast');
	
	jQuery.post('api/v1/network_connect_wlan.php', { type: 'set', interface: _interface, ssid: _ssid, psk: jQuery('input[name=password]').val() }, function(data)
	{
		if (data.status == 200)
			jQuery('div.dummy-2 strong').html('Verbindung wird getrennt...');
		else
		{
			show_error(data);
			return false;
		}
		
		jQuery.post('api/v1/network_connect_wlan.php', { type: 'down', interface: _interface }, function(data)
		{
			if (data.status == 200)
				jQuery('div.dummy-2 strong').html('Verbindung wird wieder hergestellt...');
			else
			{
				show_error(data);
				return false;
			}
			
			jQuery.post('api/v1/network_connect_wlan.php', { type: 'up', interface: _interface }, function(data)
			{
				if (data.status == 200)
					jQuery('div.dummy-2 strong').html('Ermittle IP-Adresse von Verbindung...');
				else
				{
					show_error(data);
					return false;
				}
				
				jQuery.post('api/v1/network_connect_wlan.php', { type: 'get', interface: _interface }, function(data)
				{
					if (data.data.ip != 'no ip')
					{
						jQuery('div.dummy-2 span').removeClass('svg-network-signal-animate').addClass('svg-network-signal-100');
						jQuery('div.dummy-2 strong').html('Verbindung mit "'+_ssid+'" war erfolgreich.').addClass('green');
						jQuery('div.dummy-2').append('<br /><br /><strong>IP-Adresse:</strong> <a href="http://'+data.data.ip+'" target="_blank">'+data.data.ip+'</a>');
					}
					else
					{
						show_error(data);
						return false;
					}
				});
			});
		});
	});
	
	return false;
});

jQuery(document).on('click', 'a[href=#try_again]', function(e)
{
	jQuery('input[name=password]').prop('disabled', false);
	jQuery('div.dummy-3').slideUp('fast');
	jQuery('div.dummy-1').slideDown('fast');
	
	return false;
});