<?php if (!defined('PICONTROL')) exit(); ?>
<script type="text/javascript" src="public_html/js/settings.troubleshooting.cron_selection.js"></script>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Problembehandlung'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Die Problembehandlung kann dir bei Problemen helfen und bei Bedarf diese sogar beseitigen.'); ?>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Dateien und Ordner'); ?></span>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless">
				<tr>
					<th><?php _e('Datei'); ?> / <?php _e('Ordner'); ?></th>
					<th class="table-center"><?php _e('Existiert'); ?></th>
					<th class="table-center"><?php _e('Berechtigung'); ?></th>
					<th class="table-center"><?php _e('Benutzer:Gruppe'); ?></th>
					<th class="table-center"><?php _e('Gr&ouml;ße'); ?></th>
				</tr>
<?php foreach ($data['filesFolders'] as $name => $fileFolder) { ?>
				<tr>
					<td class="<?php echo ($fileFolder['error'] === true) ? 'red' : 'green'; ?>"><?php echo $name; ?></td>
					<td class="table-center <?php echo ($fileFolder['existsBool'] === true) ? 'green' : 'red'; ?>"><?php echo ($fileFolder['exists'] === true) ? '&#10004;' : '&#10006;'; ?></td>
					<td class="table-center <?php echo ($fileFolder['permissionBool'] === true) ? 'green' : 'red'; ?>"><?php echo $fileFolder['permission']; ?></td>
					<td class="table-center <?php echo ($fileFolder['userGroupBool'] === true) ? 'green' : 'red'; ?>"><?php echo $fileFolder['userGroup']; ?></td>
					<td class="table-center <?php echo ($fileFolder['filesizeBool'] === true) ? 'green' : 'red'; ?>"><?php echo sizeUnit($fileFolder['filesize']); ?></td>
				</tr>
<?php } ?>
			</table>
		</div>
<?php if ($data['filesFoldersError'] === true) { ?>
		<div class="inner-end">
			<a href="<?php echo $data['configHelp']; ?>#wie-behebe-ich-probleme-mit-fehlenden-oder-fehlerhaften-dateien-und-ordnern" target="_blank" class="button"><?php _e('Anleitung zur Problembehebung'); ?></a>
		</div>
<?php } ?>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Cron f&uuml;r Pi Control'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Der Cron f&uuml;r dein Pi Control sieht folgendermaßen aus:'); ?><br /><br />
			<span id="cron-entry" style="padding: 5px; background: #EFEFEF; display: inline-block; font-family: Consolas, 'Andale Mono', 'Lucida Console', 'Lucida Sans Typewriter', Monaco, 'Courier New', monospace" onDblClick="selecttxt('cron-entry')"><?php echo $data['cronEntry']; ?></span> <span class="small-info"><?php _e('Mit Doppelklick alles markieren.'); ?></span>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless table-form">
				<tr>
					<td><?php _e('In Crontab eingetragen'); ?></td>
					<td class="<?php echo ($data['cronMatch'] === 1) ? 'green' : 'red'; ?>"><?php echo ($data['cronMatch'] === 1) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td><?php _e('PHP5-CLI installiert'); ?></td>
					<td class="<?php echo ($data['cronPHPCLI']) ? 'green' : 'red'; ?>"><?php echo ($data['cronPHPCLI']) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td><?php _e('Letzte Ausf&uuml;hrung'); ?></td>
					<td class="<?php echo ($data['cronLastExecutionBool']) ? 'green' : 'red'; ?>"><?php echo $data['cronLastExecution']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Letzter Eintrag Log'); ?></td>
					<td class="<?php echo ($data['cronLastExecutionLogBool']) ? 'green' : 'red'; ?>"><?php echo $data['cronLastExecutionLog']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Berechtigung'); ?></td>
					<td class="<?php echo ($data['cronPermissionBool']) ? 'green' : 'red'; ?>"><?php echo $data['cronPermission']; ?></td>
				</tr>
				<tr>
					<td><?php _e('Benutzer:Gruppe'); ?></td>
					<td class="<?php echo ($data['cronUserGroupBool']) ? 'green' : 'red'; ?>"><?php echo $data['cronUserGroup']; ?></td>
				</tr>
			</table>
		</div>
<?php if ($data['cronError'] === 1) { ?>
		<div class="inner-end">
			<form action="?s=settings&amp;do=troubleshooting" method="post"><input type="submit" name="cronSubmit" value="<?php _e('Problem beheben'); ?>" /></form>
		</div>
<?php } elseif ($data['cronError'] === 2) { ?>
		<div class="inner-end">
			<a href="<?php echo $data['configHelp']; ?>#wie-behebe-ich-probleme-mit-einem-fehlerhaften-cron-fuer-das-pi-control" target="_blank" class="button"><?php _e('Anleitung zur Problembehebung'); ?></a>
		</div>
<?php } ?>
	</div>
</div>