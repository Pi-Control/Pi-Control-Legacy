jQuery(document).on('ready', function(e)
{
	var msgBuffer = '';
	
	var wsUri = 'ws://' + ip + ':' + port+ '/?' + cookie;
	websocket = new WebSocket(wsUri);
	
	websocket.onopen = function(ev)
	{
		jQuery('#status').text(_t('Verbindung herstellen...'));
	}

	jQuery(document).on('click', '#submit', function(e)
	{
		var mymessage = jQuery('#command').val();
		
		if(mymessage == '')
		{
			alert(_t('Bitte gebe einen Befehl ein!'));
			return;
		}
		
		var msg = { message: mymessage };
		websocket.send(JSON.stringify(msg));
		
		jQuery('#command').val('');
	});
	
	jQuery(document).on('click', 'input[name=close]', function(e)
	{
		var mymessage = '^PI';
		var msg = { message: mymessage };
		websocket.send(JSON.stringify(msg));
	});
	
	jQuery(document).on('click', '#cancel', function(e)
	{
		var mymessage = '^C';
		var msg = { message: mymessage };
		websocket.send(JSON.stringify(msg));
	});
	
	websocket.onmessage = function(ev)
	{
		var msg = JSON.parse(ev.data);
		var type = msg.type;
		var umsg = msg.message;
		
		if(type == 'console')
		{
			jQuery('#terminal').append('<span class="console">' + umsg + '</span>');
			jQuery('#terminal').animate({scrollTop: jQuery('#terminal')[0].scrollHeight}, 'fast');
		}
		
		if(type == 'system')
		{
			umsg = (umsg == 'connected') ? _t('Verbunden') : umsg;
			jQuery('#status').text(umsg);
			jQuery('#command').removeAttr('disabled');
			msgBuffer = umsg;
			
			jQuery('select[name=terminal] option[value=' + port + ']').text(jQuery('select[name=terminal] option[value=' + port + ']').text().substr(0, 11) + '(' + _t('Online') + ')');
			jQuery('select[name=terminal] option[value=' + port + ']').css('background-color', '#73CA3C');
		}
	};
	
	websocket.onerror = function(ev)
	{
		jQuery('#status').text(_t('Fehler aufgetreten!'));
		jQuery('#command').attr('disabled', 'disabled');
		
		jQuery('input[name=close]').attr('name', 'reload').val(_t('Verbindung erneut herstellen'));
	};
	
	websocket.onclose = function(ev)
	{
		if (msgBuffer == 'newSession')
			jQuery('#status').html(_t('Verbindung getrennt<br />(Anmeldung an anderem Fenster)'));
		else if (msgBuffer == 'denied')
			jQuery('#status').html(_t('Verbindung getrennt<br />(Keine Berechtigung)'));
		else
		{
			jQuery('#status').text(_t('Verbindung getrennt'));
			jQuery('select[name=terminal] option[value=' + port + ']').text(jQuery('select[name=terminal] option[value=' + port + ']').text().substr(0, 11) + '(' + _t('Offline') + ')');
			jQuery('select[name=terminal] option[value=' + port + ']').css('background-color', '#E9492E');
		}
		
		jQuery('#command').attr('disabled', 'disabled');
		jQuery('input[name=close]').attr('name', 'reload').val(_t('Verbindung erneut herstellen'));
	};
	
	jQuery(document).on('change', 'select[name=terminal]', function(e)
	{
		window.document.location.href = '?s=terminal&port=' + this.value;
	});
	
	jQuery(document).on('click', 'input[name=reload]', function(e)
	{
		window.document.location.href = '?s=terminal&port=' + port;
	});
	
	jQuery("#frame").load('resources/library/terminal/terminal.php?port=' + port);
});