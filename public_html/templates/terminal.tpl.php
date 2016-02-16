<?php if (!defined('PICONTROL')) exit(); ?>
<style>
.system_msg{color: #01a7db; font-style: italic;}
.user_name{font-weight:bold;}
.user_message{color: #FFFFFF;}
pre { margin: 0; }
</style>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	//create a new WebSocket object.
	var wsUri = "ws://<?php echo $_SERVER["SERVER_NAME"]; ?>:9001";
	websocket = new WebSocket(wsUri);
	
	websocket.onopen = function(ev) { // connection is open
		//$('#terminal').append("<div class=\"system_msg\">Connected!</div>"); //notify user
		$('#status').text('Verbindung herstellen...');
	}

	$('#submit').click(function(){ //use clicks message send button
		var mymessage = $('#command').val(); //get message text
		
		if(mymessage == ""){ //emtpy message?
			alert("Enter Some message Please!");
			return;
		}
		
		//prepare json data
		var msg = {
			message: mymessage
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
		
		$('#command').val('');
	});
	
	$('#close').click(function(){ //use clicks message send button
		var mymessage = '^PI';
		
		var msg = {
		message: mymessage
		};
		
		websocket.send(JSON.stringify(msg));
	});
	
	$('#cancel').click(function(){ //use clicks message send button
		var mymessage = '^C';
		
		var msg = {
		message: mymessage
		};
		
		websocket.send(JSON.stringify(msg));
	});
	
	//#### Message received from server?
	websocket.onmessage = function(ev) {
		var msg = JSON.parse(ev.data); //PHP sends Json data
		var type = msg.type; //message type
		var umsg = msg.message; //message text
		
		if(type == 'console')
		{
			$('#terminal').append("<span class=\"console\">"+umsg+"</span>");
			$('#terminal').animate({scrollTop: $('#terminal')[0].scrollHeight}, 'fast');
		}
		
		if(type == 'system')
		{
			//$('#terminal').append("<div class=\"system_msg\">"+umsg+"</div>");
			$('#status').text(umsg);
			$('#command').removeAttr('disabled');
		}
		
		//$('#command').val(''); //reset text
	};
	
	//websocket.onerror = function(ev){$('#terminal').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");};
	//websocket.onclose = function(ev){$('#terminal').append("<div class=\"system_msg\">Connection Closed</div>");};
	
	websocket.onerror = function(ev)
	{
		$('#status').text("Error Occurred - "+ev.data);
		$('#command').attr('disabled', 'disabled');
	};
	
	websocket.onclose = function(ev)
	{
		$('#status').text("Verbindung getrennt");
		$('#command').attr('disabled', 'disabled');
	};
	
	window.onbeforeunload = function(e)
	{
		var mymessage = '^PI';
		
		var msg = {
		message: mymessage
		};
		
		websocket.send(JSON.stringify(msg));
	};
	
	$(document).on('ready', function(e)
	{
		//document.getElementById("frame").src = "resources/library/terminal/terminal.php";
		$("#frame").load("resources/library/terminal/terminal.php");
	});
});
</script>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span>Status</span>
		</div>
		<div class="inner">
			<strong id="status">Lade...</strong>
			<div id="frame"></div>
		</div>
		<div class="inner-end">
			<input type="button" id="close" value="Verbindung trennen" />
		</div>
	</div>
</div>
<!-- Container -->
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span>Terminal</span>
		</div>
		<div class="inner">
			Das Terminal bietet dir die Möglichkeit, einfache Befehle direkt im Pi Control auszuführen.
		</div>
		<div class="inner overflow-auto" id="terminal" style="background: #000000; color: #CCCCCC; padding: 5px; font-family: monospace; height: 360px;"></div>
		<div class="inner" style="padding-top: 15px;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 5%;">Befehl: </td>
					<td><input type="text" name="command" id="command" style="width: 100%; box-sizing: border-box;" /></td>
					<td style="width: 5%;"><input type="button" id="submit" value="Abschicken" /></td>
				</tr>
			</table>
			<br /><input type="button" id="cancel" value="Strg + C" />
		</div>
	</div>
</div>
<div class="clear_both"></div>