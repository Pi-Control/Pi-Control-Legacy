<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zum Benutzer'); ?></span>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless">
				<tr>
					<th style="width: 30%;"><?php _e('Benutzername'); ?></th>
					<th style="width: 20%;" class="table-center"><?php _e('Erstellt'); ?></th>
					<th style="width: 20%;" class="table-center"><?php _e('Letzte Aktivit&auml;t'); ?></th>
					<th style="width: 30%;"></th>
				</tr>
<?php foreach ($data['allUsers'] as $key => $user) { ?>
				<tr>
					<td><?php echo $user['username']; ?></td>
					<td class="table-center"><?php echo date('d.m.Y H:i', $user['created']); ?></td>
					<td class="table-center"><?php echo ($user['last_login'] == 0) ? _t('Noch nie angemeldet') : date('d.m.Y H:i', $user['last_login']); ?></td>
					<td class="table-right"><a href="?s=settings&amp;do=user&amp;edit=<?php echo substr($key, 5); ?>" class="button button-small"><?php _e('Bearbeiten'); ?></a> <a href="?s=settings&amp;do=user&amp;delete=<?php echo substr($key, 5); ?>" class="button button-small"><?php _e('L&ouml;schen'); ?></a></td>
				</tr>
<?php } ?>
			</table>
		</div>
		<div class="inner-end">
			<a href="?s=settings&amp;do=user&amp;add" class="button"><?php _e('Hinzuf&uuml;gen'); ?></a>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Angemeldete Benutzer'); ?></span>
		</div>
		<div class="inner-info">
			<div><?php _e('Benutzer ohne fester Anmeldung werden nach 12 Stunden Inaktivit&auml;t automatisch abgemeldet.'); ?></div>
		</div>
		<div class="inner-table overflow-auto">
			<form action="?s=settings&amp;do=user" method="post">
				<table class="table table-borderless">
					<tr>
						<th style="width: 30%;"><?php _e('Benutzername'); ?></th>
						<th style="width: 20%;" class="table-center"><?php _e('Angemeldet am'); ?></th>
						<th style="width: 20%;" class="table-center"><?php _e('Angemeldet von'); ?></th>
						<th style="width: 20%;" class="table-center"><?php _e('Angemeldet bleiben'); ?></th>
						<th style="width: 10%;"></th>
					</tr>
<?php foreach ($data['loggedInUsers'] as $key => $user) { ?>
					<tr>
						<td><?php echo $user['username']; if ($user['current_online']) { ?> <strong class="green">[<?php _e('Aktuelle Sitzung'); ?>] </strong><?php } ?></td>
						<td class="table-center"><?php echo date('d.m.Y H:i', $user['created']); ?></td>
						<td class="table-center"><?php echo $user['address']; ?></td>
						<td class="table-center"><?php echo ($user['remember_me'] === true) ? _t('Ja') : _t('Nein'); ?></td>
						<td class="table-right"><button class="button-small" name="logout" value="<?php echo substr($key, 6); ?>"><?php _e('Abmelden'); ?></button></td>
					</tr>
<?php } ?>
				</table>
			</form>
		</div>
	</div>
</div>