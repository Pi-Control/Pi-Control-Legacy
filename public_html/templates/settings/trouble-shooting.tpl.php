<?php if (!defined('PICONTROL')) exit(); ?>
<script type="text/javascript" src="public_html/js/settings.trouble-shooting.cron_selection.js"></script>
<div>
	<div class="box">
		<div class="inner-header">
			<span>Problembehandlung</span>
		</div>
		<div class="inner">
			Die Problembehandlung kann dir bei Problemen helfen und bei Bedarf diese sogar beseitigen.
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span>Dateien und Ordner</span>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless">
				<tr>
					<th>Datei / Ordner</th>
					<th class="table-center">Existiert</th>
					<th class="table-center">Berechtigung</th>
					<th class="table-center">Benutzer / Gruppe</th>
					<th class="table-center">Gr&ouml;ße</th>
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
			<a href="#" target="_blank" class="button">Anleitung zur Problembehebung</a>
		</div>
<?php } ?>
	</div>
	<div class="box">
		<div class="inner-header">
			<span>Cron f&uuml;r Pi Control</span>
		</div>
		<div class="inner">
			Der Cron f&uuml;r dein Pi Control sieht folgendermaßen aus:<br /><br />
			<span id="cron-entry" style="padding: 5px; background: #EFEFEF; display: inline-block; font-family: Consolas, 'Andale Mono', 'Lucida Console', 'Lucida Sans Typewriter', Monaco, 'Courier New', monospace" onDblClick="selecttxt('cron-entry')"><?php echo $data['cronEntry']; ?></span> <span class="small-info">Mit Doppelklick alles markieren.</span>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless table-form">
				<tr>
					<td>In Crontab eingetragen</td>
					<td class="<?php echo ($data['cronMatch'] === 1) ? 'green' : 'red'; ?>"><?php echo ($data['cronMatch'] === 1) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td>PHP5-CLI installiert</td>
					<td class="<?php echo ($data['cronPHPCLI']) ? 'green' : 'red'; ?>"><?php echo ($data['cronPHPCLI']) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td>Letzte Ausf&uuml;hrung</td>
					<td class="<?php echo ($data['cronLastExecutionBool']) ? 'green' : 'red'; ?>"><?php echo $data['cronLastExecution']; ?></td>
				</tr>
				<tr>
					<td>Letzter Eintrag Log</td>
					<td class="<?php echo ($data['cronLastExecutionLogBool']) ? 'green' : 'red'; ?>"><?php echo $data['cronLastExecutionLog']; ?></td>
				</tr>
				<tr>
					<td>Berechtigung</td>
					<td class="<?php echo ($data['cronPermissionBool']) ? 'green' : 'red'; ?>"><?php echo $data['cronPermission']; ?></td>
				</tr>
				<tr>
					<td>Benutzer / Gruppe</td>
					<td class="<?php echo ($data['cronUserGroupBool']) ? 'green' : 'red'; ?>"><?php echo $data['cronUserGroup']; ?></td>
				</tr>
			</table>
		</div>
<?php if ($data['cronError'] === 1) { ?>
		<div class="inner-end">
			<form action="?s=settings&amp;do=trouble-shooting" method="post"><input type="submit" name="cronSubmit" value="Problem beheben" /></form>
		</div>
<?php } elseif ($data['cronError'] === 2) { ?>
		<div class="inner-end">
			<a href="#" target="_blank" class="button">Anleitung zur Problembehebung</a>
		</div>
<?php } ?>
	</div>
</div>