var dummy2Msg = '';

function showError(msg)
{
	if (msg != undefined)
	{
		console.log(msg);
		jQuery('div.dummy-3 strong span').html(msg.data.errorMsg);
	}
	else
	{
		alert(_t('Es ist ein unerwarteter Fehler aufgetreten!'));
 		jQuery('div.dummy-3 strong span').html(_t('Es ist ein unerwarteter Fehler aufgetreten!'));
	}
	
	jQuery('div.dummy-2').slideUp('fast');
	jQuery('div.dummy-3').slideDown('fast');
}

jQuery(document).on('click', 'input[name=submit]', function(e)
{
	if (jQuery('input[name=password]').length == 1 && jQuery('input[name=password]').val().length < 8 && jQuery('input[name=password]').attr('data') == null)
	{
		alert(_t('Das Passwort sollte mindestens 8 Zeichen betragen.'));
		return false;
	}
	
	if (jQuery('input[name=password]').length == 1)
		jQuery('input[name=password]').prop('disabled', true);
		
	if (dummy2Msg == '')
		dummy2Msg = jQuery('div.dummy-2 strong').html();
	else
		jQuery('div.dummy-2 strong').html(dummy2Msg);
	
	jQuery('div.dummy-1').slideUp('fast');
	jQuery('div.dummy-2').slideDown('fast');
	
	jQuery.post('api/v1/network_connect_wlan.php', { type: 'set', interface: _interface, ssid: _ssid, psk: jQuery('input[name=password]').val() }, function(data)
	{
		if (data.status == 200)
			jQuery('div.dummy-2 strong').html(_t('Verbindung wird getrennt...'));
		else
		{
			showError(data);
			return false;
		}
		
		jQuery.post('api/v1/network_connect_wlan.php', { type: 'down', interface: _interface }, function(data)
		{
			if (data.status == 200)
				jQuery('div.dummy-2 strong').html(_t('Verbindung wird wieder hergestellt...'));
			else
			{
				showError(data);
				return false;
			}
			
			jQuery.post('api/v1/network_connect_wlan.php', { type: 'up', interface: _interface }, function(data)
			{
				if (data.status == 200)
					jQuery('div.dummy-2 strong').html(_t('Ermittle IP-Adresse von Verbindung...'));
				else
				{
					showError(data);
					return false;
				}
				
				jQuery.post('api/v1/network_connect_wlan.php', { type: 'get', interface: _interface }, function(data)
				{
					if (data.data.ip != 'no ip')
					{
						jQuery('div.dummy-2 span').removeClass('svg-network-signal-animate').addClass('svg-network-signal-100');
						jQuery('div.dummy-2 strong').html(_t('Verbindung mit "%%s" war erfolgreich.', _ssid)).addClass('green');
						jQuery('div.dummy-2').append('<br /><br /><strong>' + _t('IP-Adresse') + ':</strong> <a href="http://'+data.data.ip+'" target="_blank">'+data.data.ip+'</a>');
					}
					else
					{
						showError(data);
						return false;
					}
				}).fail(function(e) { showError(); });
			}).fail(function(e) { showError(); });
		}).fail(function(e) { showError(); });
	}).fail(function(e) { showError(); });
	
	return false;
});

jQuery(document).on('click', 'a[href=#try_again]', function(e)
{
	jQuery('input[name=password]').prop('disabled', false);
	jQuery('div.dummy-3').slideUp('fast');
	jQuery('div.dummy-1').slideDown('fast');
	
	return false;
});