<?php if (!defined('PICONTROL')) exit(); ?>
<!-- Container -->
<div>
	<div class="box">
		<div class="inner-header">
			<span>Terminal</span>
		</div>
		<div class="inner">
			Das Terminal bietet dir die Möglichkeit, einfache Befehle direkt im Pi Control auszuführen.
		</div>
		<div class="inner" style="background: #000000; color: #CCCCCC; padding: 15px; font-family: monospace; min-height: 300px;"><?php echo $data['test']; ?>
		</div>
		<form action="?s=terminal" method="post" class="margin-0">
			<div class="inner">
				<br />Eingabe: <input type="text" name="command" style="width: 80%;" /> <input type="submit" name="submit" value="Abschicken" style="padding: 6px 16px 6px 16px;" />
			</div>
		</form>
	</div>
</div>
<div class="clear_both"></div>