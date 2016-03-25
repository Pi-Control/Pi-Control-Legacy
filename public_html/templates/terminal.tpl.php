<?php if (!defined('PICONTROL')) exit(); ?>
<style>
.system_msg{color: #01a7db; font-style: italic;}
.user_name{font-weight:bold;}
.user_message{color: #FFFFFF;}
pre { margin: 0; }
</style>
<script language="javascript" type="text/javascript">
var ip = '<?php echo $_SERVER['SERVER_NAME']; ?>';
var port = <?php echo $data['port']; ?>;
var cookie = '<?php echo $data['cookie']; ?>';
</script>
<script language="javascript" type="text/javascript" src="public_html/js/terminal.websocket.js"></script>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span>Status</span>
		</div>
		<div class="inner">
			<strong id="status">Lade...</strong><br /><br />
			<select name="terminal">
<?php foreach ($data['ports'] as $index => $port) { ?>
				<option style="background: <?php echo ($port['active'] === true) ? '#73CA3C' : '#E9492E'; ?>;" value="<?php echo $port['port']; ?>"<?php if ($data['port'] == $port['port']) echo ' selected="selected"'; ?>>Terminal <?php echo $index+1; ?> (<?php echo ($port['active'] === true) ? 'Online' : 'Offline'; ?>)</option>
<?php } ?>
			</select>
			<div id="frame"></div>
		</div>
		<div class="inner-end">
			<input type="button" name="close" value="Verbindung trennen" />
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