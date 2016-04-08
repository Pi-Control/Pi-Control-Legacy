<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Problembehandlung'); ?></span>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless">
				<tr>
					<th><?php _e('Datei / Ordner'); ?></th>
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
</div>