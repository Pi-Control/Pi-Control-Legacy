$(document).on('ready', function(e)
{
	var wsUri = 'ws://' + ip + ':' + port;
	websocket = new WebSocket(wsUri);
	
	websocket.onopen = function(ev)
	{
		$('#status').text('Verbindung herstellen...');
	}

	$(document).on('click', '#submit', function(e)
	{
		var mymessage = $('#command').val();
		
		if(mymessage == '')
		{
			alert('Bitte gebe einen Befehl ein!');
			return;
		}
		
		var msg = { message: mymessage };
		websocket.send(JSON.stringify(msg));
		
		$('#command').val('');
	});
	
	$(document).on('click', 'input[name=close]', function(e)
	{
		var mymessage = '^PI';
		var msg = { message: mymessage };
		websocket.send(JSON.stringify(msg));
	});
	
	$(document).on('click', '#cancel', function(e)
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
			$('#terminal').append('<span class="console">' + umsg + '</span>');
			$('#terminal').animate({scrollTop: $('#terminal')[0].scrollHeight}, 'fast');
		}
		
		if(type == 'system')
		{
			$('#status').text(umsg);
			$('#command').removeAttr('disabled');
			
			$('select[name=terminal] option[value=' + port + ']').text($('select[name=terminal] option[value=' + port + ']').text().substr(0, 11) + '(Online)');
			$('select[name=terminal] option[value=' + port + ']').css('background-color', '#73CA3C');
		}
	};
	
	websocket.onerror = function(ev)
	{
		$('#status').text('Fehler aufgetreten!');
		$('#command').attr('disabled', 'disabled');
		
		$('input[name=close]').attr('name', 'reload').val('Verbindung erneut herstellen');
	};
	
	websocket.onclose = function(ev)
	{
		$('#status').text('Verbindung getrennt');
		$('#command').attr('disabled', 'disabled');
		
		$('select[name=terminal] option[value=' + port + ']').text($('select[name=terminal] option[value=' + port + ']').text().substr(0, 11) + '(Offline)');
		$('select[name=terminal] option[value=' + port + ']').css('background-color', '#E9492E');
		
		$('input[name=close]').attr('name', 'reload').val('Verbindung erneut herstellen');
	};
	
	$(document).on('change', 'select[name=terminal]', function(e)
	{
		window.document.location.href = '?s=terminal&port=' + this.value;
	});
	
	$(document).on('click', 'input[name=reload]', function(e)
	{
		window.document.location.href = '?s=terminal&port=' + port;
	});
	
	$("#frame").load('resources/library/terminal/terminal.php?port=' + port);
});