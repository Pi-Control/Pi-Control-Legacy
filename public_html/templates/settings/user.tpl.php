<div>
	<div class="box">
		<div class="inner-header">
			<span>Einstellungen zum Benutzer</span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 50%;">Benutzername</th>
					<th style="width: 25%;" class="table-center">Erstellt</th>
					<th style="width: 25%;" class="table-center">Letzte Aktivit&auml;t</th>
				</tr>
<?php foreach ($data['allUsers'] as $user) { ?>
				<tr>
					<td><?php echo $user['username']; ?></td>
					<td class="table-center"><?php echo date('d.m.Y H:i', $user['created']); ?></td>
					<td class="table-center"><?php echo date('d.m.Y H:i', $user['last_login']); ?></td>
				</tr>
<?php } ?>
			</table>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span>Angemeldete Benutzer</span>
		</div>
		<div class="inner-info">
			<div>Benutzer ohne fester Anmeldung werden nach 12 Stunden Inaktivit&auml;t automatisch abgemeldet.</div>
		</div>
		<div class="inner-table settings-user-logged-in-users">
			<form action="?s=settings&amp;do=user" method="post">
				<table class="table table-borderless">
					<tr>
						<th style="width: 30%;">Benutzername</th>
						<th style="width: 20%;" class="table-center">Angemeldet am</th>
						<th style="width: 20%;" class="table-center">Angemeldet von</th>
						<th style="width: 20%;" class="table-center">Angemeldet bleiben</th>
						<th style="width: 10%;"></th>
					</tr>
<?php foreach ($data['loggedInUsers'] as $key => $user) { ?>
					<tr>
						<td><?php echo $user['username']; if ($user['current_online']) { ?> <strong class="green">[<?php _e('Aktuelle Sitzung'); ?>] </strong><?php } ?></td>
						<td class="table-center"><?php echo date('d.m.Y H:i', $user['created']); ?></td>
						<td class="table-center"><?php echo $user['address']; ?></td>
						<td class="table-center"><?php echo ($user['keep_logged_in'] === true) ? 'Ja' : 'Nein'; ?></td>
						<td class="table-right"><button class="button-small" name="logout" value="<?php echo substr($key, 6); ?>">Abmelden</button></td>
					</tr>
<?php } ?>
				</table>
			</form>
		</div>
	</div>
</div>